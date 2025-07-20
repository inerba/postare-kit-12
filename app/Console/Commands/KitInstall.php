<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use PDOException;

class KitInstall extends Command
{
    protected $signature = 'kit:install {--force : Force installation without confirmation}';

    protected $description = 'Installa e configura il progetto Laravel';

    private const ENV_KEYS_TO_COMMENT_FOR_SQLITE = ['DB_HOST', 'DB_PORT', 'DB_USERNAME', 'DB_PASSWORD'];

    private const DB_SPECIFIC_KEYS = ['DB_DATABASE', 'DB_CONNECTION'];

    private const DEFAULT_ADMIN_DATA = [
        'name' => 'John Doe',
        'email' => 'admin@example.com',
        'password' => 'password',
    ];

    private string $envPath;

    private string $envContent;

    public function handle(): int
    {
        $this->initializeEnvironment();

        if (! $this->setupEnvironmentFile()) {
            return self::FAILURE;
        }

        $installationData = $this->gatherInstallationData();
        if ($installationData === null) {
            return self::SUCCESS; // User cancelled
        }

        return $this->executeInstallation($installationData);
    }

    private function initializeEnvironment(): void
    {
        $this->envPath = base_path('.env');
        $this->envContent = File::exists($this->envPath) ? File::get($this->envPath) : '';
    }

    private function setupEnvironmentFile(): bool
    {
        if (! File::exists($this->envPath)) {
            $envExamplePath = base_path('.env.example');

            if (! File::exists($envExamplePath)) {
                $this->error('File .env.example non trovato!');

                return false;
            }

            File::copy($envExamplePath, $this->envPath);
            $this->envContent = File::get($this->envPath);
            $this->info('Creato file .env da .env.example');
        }

        return true;
    }

    private function gatherInstallationData(): ?array
    {
        $isAlreadyInstalled = $this->isProjectInstalled();

        if ($isAlreadyInstalled && ! $this->shouldOverwriteInstallation()) {
            $this->info('Installazione annullata.');

            return null;
        }

        $useFresh = $isAlreadyInstalled && $this->shouldUseFreshMigration();

        if (! $isAlreadyInstalled) {
            $this->generateAppKey();
        }

        return [
            'admin' => $this->gatherAdminData(),
            'project' => $this->gatherProjectData(),
            'database' => $this->gatherDatabaseData(),
            'use_fresh' => $useFresh,
        ];
    }

    private function isProjectInstalled(): bool
    {
        $appKey = $this->getEnvValue('APP_KEY');

        return ! empty($appKey);
    }

    private function shouldOverwriteInstallation(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        return $this->confirm('Il progetto risulta già installato. Vuoi sovrascrivere l\'installazione?', false);
    }

    private function shouldUseFreshMigration(): bool
    {
        $this->warn('Attenzione: l\'installazione precedente verrà sovrascritta!');

        return $this->confirm('Vuoi eseguire migrate:fresh (ATTENZIONE: tutti i dati verranno persi)?', false);
    }

    private function generateAppKey(): void
    {
        Artisan::call('key:generate', ['--force' => true]);
        $this->info('APP_KEY generata automaticamente');
    }

    private function gatherAdminData(): array
    {
        $defaults = [
            'name' => $this->getEnvValue('DEFAULT_USER_NAME', self::DEFAULT_ADMIN_DATA['name']),
            'email' => $this->getEnvValue('DEFAULT_USER_EMAIL', self::DEFAULT_ADMIN_DATA['email']),
            'password' => $this->getEnvValue('DEFAULT_USER_PASSWORD', self::DEFAULT_ADMIN_DATA['password']),
        ];

        $adminData = [
            'name' => $this->ask('Nome utente amministratore?', $defaults['name']),
            'email' => $this->ask('Email amministratore?', $defaults['email']),
            'password' => $this->ask('Password amministratore?', $defaults['password']),
        ];

        $this->updateEnv('DEFAULT_USER_NAME', $adminData['name']);
        $this->updateEnv('DEFAULT_USER_EMAIL', $adminData['email']);
        $this->updateEnv('DEFAULT_USER_PASSWORD', $adminData['password']);

        return $adminData;
    }

    private function gatherProjectData(): array
    {
        $projectDir = basename(base_path());
        $defaultProjectName = ucwords(str_replace(['-', '_'], ' ', $projectDir));
        $slug = Str::slug($projectDir);

        $projectData = [
            'name' => $this->ask('Come si chiama il progetto?', $defaultProjectName),
            'url' => $this->ask('Qual è l\'APP_URL?', "http://{$slug}.test"),
            'slug' => $slug,
        ];

        $this->updateEnv('APP_NAME', $projectData['name']);
        $this->updateEnv('APP_URL', $projectData['url']);

        return $projectData;
    }

    private function gatherDatabaseData(): array
    {
        $dbType = $this->choice('Tipo di database?', ['sqlite', 'mysql', 'pgsql'], 0);
        $this->updateEnv('DB_CONNECTION', $dbType);

        if ($dbType === 'sqlite') {
            return $this->configureSqlite();
        }

        return $this->configureRelationalDatabase($dbType);
    }

    private function configureSqlite(): array
    {
        $sqlitePath = database_path('database.sqlite');
        $this->updateEnv('DB_DATABASE', $sqlitePath);
        $this->commentEnvKeys(self::ENV_KEYS_TO_COMMENT_FOR_SQLITE);

        return [
            'type' => 'sqlite',
            'path' => $sqlitePath,
        ];
    }

    private function configureRelationalDatabase(string $dbType): array
    {
        $currentConfig = $this->getCurrentDatabaseConfig();
        $defaultPort = $dbType === 'mysql' ? '3306' : '5432';

        $config = [
            'type' => $dbType,
            'name' => $this->ask('Nome del database?', basename(base_path())),
            'host' => $this->ask('Host del database?', $currentConfig['host'] ?? '127.0.0.1'),
            'port' => $this->ask('Porta del database?', $currentConfig['port'] ?? $defaultPort),
            'username' => $this->ask('Username database?', $currentConfig['username'] ?? 'root'),
            'password' => $this->ask('Password database?', $currentConfig['password'] ?? ''),
        ];

        $this->updateEnv('DB_DATABASE', $config['name']);
        $this->updateEnv('DB_HOST', $config['host']);
        $this->updateEnv('DB_PORT', $config['port']);
        $this->updateEnv('DB_USERNAME', $config['username']);
        $this->updateEnv('DB_PASSWORD', $config['password']);
        $this->uncommentEnvKeys(self::ENV_KEYS_TO_COMMENT_FOR_SQLITE);

        return $config;
    }

    private function getCurrentDatabaseConfig(): array
    {
        return [
            'host' => $this->getEnvValue('DB_HOST'),
            'port' => $this->getEnvValue('DB_PORT'),
            'username' => $this->getEnvValue('DB_USERNAME'),
            'password' => $this->getEnvValue('DB_PASSWORD'),
        ];
    }

    private function executeInstallation(array $data): int
    {
        try {
            $this->setupDatabase($data['database'], $data['use_fresh']);
            $this->runSeeders();
            $this->setupSuperAdmin();
            $this->buildAssets();

            $this->info('Installazione completata!');

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error("Errore durante l'installazione: ".$e->getMessage());

            return self::FAILURE;
        }
    }

    private function setupDatabase(array $dbConfig, bool $useFresh): void
    {
        $this->info('Esecuzione migration...');

        if ($dbConfig['type'] === 'sqlite') {
            $this->setupSqliteDatabase($dbConfig['path'], $useFresh);
            Artisan::call('migrate', ['--force' => true]);
        } else {
            $this->setupRelationalDatabase($dbConfig, $useFresh);
        }

        $this->line(Artisan::output());
    }

    private function setupSqliteDatabase(string $path, bool $recreate): void
    {
        if (! File::exists($path)) {
            File::put($path, '');
            $this->info('File SQLite creato: '.$path);
        } elseif ($recreate) {
            File::delete($path);
            File::put($path, '');
            $this->info('File SQLite ricreato: '.$path);
        }
    }

    private function setupRelationalDatabase(array $config, bool $useFresh): void
    {
        $databaseExists = $this->databaseExists();
        $command = ($useFresh && $databaseExists) ? 'migrate:fresh' : 'migrate';
        Artisan::call($command, ['--force' => true]);
    }

    private function runSeeders(): void
    {
        $this->info('Installazione dati demo...');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
        $this->line(Artisan::output());
    }

    private function setupSuperAdmin(): void
    {
        $this->info('Aggiunta dell\'utente iniziale ai super admin');
        Artisan::call('shield:super-admin', ['--user' => 1]);
        $this->line(Artisan::output());
    }

    private function buildAssets(): void
    {
        $this->runNpmCommand('install', 'Installazione pacchetti npm...');
        $this->runNpmCommand('run build:all', 'Build asset frontend...');
    }

    private function runNpmCommand(string $command, string $message): void
    {
        $this->info($message);

        $result = Process::path(base_path())->run("npm {$command}");

        if ($result->successful()) {
            $this->line($result->output());
        } else {
            $this->error($result->errorOutput());
            throw new Exception("Comando npm fallito: {$command}");
        }
    }

    private function getEnvValue(string $key, string $default = ''): string
    {
        if (preg_match("/^{$key}=(.*)$/m", $this->envContent, $matches)) {
            return trim($matches[1], "\"'");
        }

        return $default;
    }

    private function updateEnv(string $key, string $value): void
    {
        if (! File::exists($this->envPath)) {
            throw new Exception('.env file non trovato!');
        }

        $pattern = "/^{$key}=.*$/m";
        $replacement = $this->formatEnvValue($key, $value);

        if (preg_match($pattern, $this->envContent)) {
            $this->envContent = preg_replace($pattern, $replacement, $this->envContent);
        } else {
            $this->envContent .= "\n{$replacement}";
        }

        File::put($this->envPath, $this->envContent);
        $this->info("Impostato {$key} in .env");
    }

    private function formatEnvValue(string $key, string $value): string
    {
        // Per specifiche chiavi DB non usiamo le virgolette
        if (in_array($key, self::DB_SPECIFIC_KEYS)) {
            return "{$key}={$value}";
        }

        return "{$key}=\"{$value}\"";
    }

    private function commentEnvKeys(array $keys): void
    {
        foreach ($keys as $key) {
            $pattern = "/^{$key}=.*$/m";
            $this->envContent = preg_replace($pattern, '# $0', $this->envContent);
        }

        File::put($this->envPath, $this->envContent);
        $this->info('Commentate variabili DB non necessarie per SQLite');
    }

    private function uncommentEnvKeys(array $keys): void
    {
        foreach ($keys as $key) {
            $pattern = "/^# {$key}=.*$/m";
            $this->envContent = preg_replace_callback($pattern, function ($matches) {
                return substr($matches[0], 2); // Rimuove "# "
            }, $this->envContent);
        }

        File::put($this->envPath, $this->envContent);
        $this->info('Ripristinate variabili DB per MySQL/PostgreSQL');
    }

    private function databaseExists(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Unknown database')) {
                return false;
            }
            throw $e;
        }
    }
}
