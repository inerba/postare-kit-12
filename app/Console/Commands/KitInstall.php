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

    /**
     * Esegue il comando di installazione principale.
     *
     * Inizializza l'ambiente, prepara il file .env, raccoglie i dati necessari e avvia l'installazione.
     *
     * @return int Codice di stato del comando
     */
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

    /**
     * Inizializza i percorsi e carica il contenuto del file .env.
     */
    private function initializeEnvironment(): void
    {
        $this->envPath = base_path('.env');
        $this->envContent = File::exists($this->envPath) ? File::get($this->envPath) : '';
    }

    /**
     * Prepara il file .env copiandolo da .env.example se necessario.
     *
     * @return bool True se il file .env esiste o è stato creato, false altrimenti
     */
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

    /**
     * Raccoglie tutti i dati necessari per l'installazione.
     *
     * @return array<string, mixed>|null Dati raccolti per l'installazione o null se annullata
     */
    private function gatherInstallationData(): ?array
    {
        $isAlreadyInstalled = $this->isProjectInstalled();

        if ($isAlreadyInstalled && ! $this->shouldOverwriteInstallation()) {
            $this->info('Installazione annullata.');

            return null;
        }

        if (! $isAlreadyInstalled) {
            $this->generateAppKey();
        }

        return [
            'admin' => $this->gatherAdminData(),
            'project' => $this->gatherProjectData(),
            'database' => $this->gatherDatabaseData(),
        ];
    }

    /**
     * Verifica se il progetto è già installato controllando la presenza di APP_KEY.
     */
    private function isProjectInstalled(): bool
    {
        $appKey = $this->getEnvValue('APP_KEY');

        return ! empty($appKey);
    }

    /**
     * Chiede conferma all'utente per sovrascrivere un'installazione esistente.
     */
    private function shouldOverwriteInstallation(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        return $this->confirm('Il progetto risulta già installato. Vuoi sovrascrivere l\'installazione?', false);
    }

    /**
     * Genera una nuova APP_KEY per l'applicazione.
     */
    private function generateAppKey(): void
    {
        Artisan::call('key:generate', ['--force' => true]);
        $this->info('APP_KEY generata automaticamente');
    }

    /**
     * Raccoglie i dati dell'utente amministratore e li aggiorna nel file .env.
     *
     * @return array<string, mixed>
     */
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

    /**
     * Raccoglie i dati del progetto (nome, url) e li aggiorna nel file .env.
     *
     * @return array<string, mixed> Dati del progetto
     */
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

    /**
     * Raccoglie e configura i dati relativi al database.
     *
     * @return array<string, mixed> Configurazione database
     */
    private function gatherDatabaseData(): array
    {
        $dbType = $this->choice('Tipo di database?', ['sqlite', 'mysql', 'mariadb', 'pgsql'], 1);
        $this->updateEnv('DB_CONNECTION', $dbType);

        if ($dbType === 'sqlite') {
            return $this->configureSqlite();
        }

        return $this->configureRelationalDatabase($dbType);
    }

    /**
     * Configura il database SQLite e aggiorna il file .env.
     *
     * @return array<string, mixed> Configurazione SQLite
     */
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

    /**
     * Configura un database relazionale (MySQL/PostgreSQL) e aggiorna il file .env.
     *
     * @param  string  $dbType  Tipo di database (mysql|pgsql)
     * @return array<string, mixed> Configurazione database relazionale
     */
    private function configureRelationalDatabase(string $dbType): array
    {
        $currentConfig = $this->getCurrentDatabaseConfig();
        $defaultPort = $dbType === 'mysql' ? '3306' : '5432';

        $config = [
            'type' => $dbType,
            'name' => $this->ask('Nome del database?', basename(base_path())),
            'host' => $this->ask('Host del database?', $currentConfig['host'] ?: '127.0.0.1'),
            'port' => $this->ask('Porta del database?', $currentConfig['port'] ?: $defaultPort),
            'username' => $this->ask('Username database?', $currentConfig['username'] ?: 'root'),
            'password' => $this->ask('Password database?', $currentConfig['password'] ?: ''),
        ];

        $this->updateEnv('DB_DATABASE', $config['name']);
        $this->updateEnv('DB_HOST', $config['host']);
        $this->updateEnv('DB_PORT', $config['port']);
        $this->updateEnv('DB_USERNAME', $config['username']);
        $this->updateEnv('DB_PASSWORD', $config['password']);
        $this->uncommentEnvKeys(self::ENV_KEYS_TO_COMMENT_FOR_SQLITE);

        return $config;
    }

    /**
     * Recupera la configurazione attuale del database dal file .env.
     *
     * @return array<string, mixed> Configurazione attuale del database
     */
    private function getCurrentDatabaseConfig(): array
    {
        return [
            'host' => $this->getEnvValue('DB_HOST'),
            'port' => $this->getEnvValue('DB_PORT'),
            'username' => $this->getEnvValue('DB_USERNAME'),
            'password' => $this->getEnvValue('DB_PASSWORD'),
        ];
    }

    /**
     * Esegue l'installazione completa: migrazioni, seeders, super admin e build asset.
     *
     * @param  array<string, mixed>  $data  Dati raccolti per l'installazione
     * @return int Codice di stato
     */
    private function executeInstallation(array $data): int
    {
        try {
            // Verifica che l'APP_KEY sia presente prima di procedere
            if (empty($this->getEnvValue('APP_KEY'))) {
                $this->generateAppKey();
            }

            $this->setupDatabase($data['database']);
            $this->setupPanelOptions();
            $this->runSeeders();
            $this->setupSuperAdmin();
            $this->createStorageLink();
            $this->buildAssets();

            // Messaggio che oltre a confermare l'installazione, fornisce il link per accedere al progetto
            $this->info('Installazione completata!');
            $this->info('Puoi accedere al progetto all\'indirizzo: '.$data['project']['url'].'/'.config('postare-kit.panel_path', 'auth'));

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error("Errore durante l'installazione: ".$e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Crea storage link
     * Questo metodo verifica se il link simbolico per la cartella storage esiste già.
     * Se non esiste, lo crea utilizzando il comando artisan 'storage:link'.
     */
    private function createStorageLink(): void
    {
        if (! File::exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $this->info('Storage link creato con successo.');
        } else {
            $this->info('Storage link già esistente.');
        }
    }

    /**
     * Configura le opzioni del pannello di controllo.
     *
     * Questo metodo può essere esteso per aggiungere opzioni specifiche del pannello.
     */
    private function setupPanelOptions(): void
    {
        // Configura il path del pannello di controllo, di default è 'auth' è configurabile tramite .env
        // Chiedi all'utente se vuole cambiare il path del pannello
        $panelPath = $this->ask('Path del pannello di controllo?', $this->getEnvValue('PANEL_PATH'));
        $this->updateEnv('PANEL_PATH', $panelPath);
    }

    /**
     * Esegue le migrazioni in base al tipo di database.
     *
     * @param  array<string, mixed>  $dbConfig  Configurazione database
     */
    private function setupDatabase(array $dbConfig): void
    {
        $this->info('Esecuzione migration...');

        if ($dbConfig['type'] === 'sqlite') {
            $this->setupSqliteDatabase($dbConfig['path']);
            Artisan::call('migrate', ['--force' => true]);
        } else {
            $this->setupRelationalDatabase($dbConfig);
        }

        $this->line(Artisan::output());
    }

    /**
     * Crea o ricrea il file SQLite se necessario.
     *
     * @param  string  $path  Percorso file SQLite
     */
    private function setupSqliteDatabase(string $path): void
    {
        // Se il file SQLite esiste, lo elimina per ricrearlo
        if (File::exists($path)) {
            File::delete($path);
        }

        File::put($path, '');
        $this->info('File SQLite: '.$path);
    }

    /**
     * Esegue le migrazioni per database relazionali.
     *
     * @param  array<string, mixed>  $config  Configurazione database
     */
    private function setupRelationalDatabase(array $config): void
    {
        $databaseExists = $this->databaseExists();
        $command = $databaseExists ? 'migrate:fresh' : 'migrate';
        Artisan::call($command, ['--force' => true]);
    }

    /**
     * Esegue i seeder principali del database.
     */
    private function runSeeders(): void
    {
        $this->info('Installazione dati di base...');

        $this->refreshConfig();

        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
        $this->line(Artisan::output());

        // chiede all'utente se vuole installare i post di esempio
        if ($this->confirm('Vuoi installare dati di esempio?', false)) {
            Artisan::call('db:seed', ['--class' => 'TagsAndCategoriesSeeder', '--force' => true]);
            $this->line(Artisan::output());
            Artisan::call('db:seed', ['--class' => 'PostSeeder', '--force' => true]);
            $this->line(Artisan::output());
        }
    }

    /**
     * Aggiunge l'utente iniziale ai super admin tramite comando artisan.
     */
    private function setupSuperAdmin(): void
    {
        $this->info('Aggiunta dell\'utente iniziale ai super admin');
        Artisan::call('shield:super-admin', ['--user' => 1]);
        $this->line(Artisan::output());
    }

    /**
     * Esegue i comandi npm per installare e buildare gli asset frontend.
     */
    private function buildAssets(): void
    {
        $this->runNpmCommand('install', 'Installazione pacchetti npm...');
        $this->runNpmCommand('run build:all', 'Build asset frontend...');
    }

    /**
     * Esegue un comando npm e gestisce l'output.
     *
     * @param  string  $command  Comando npm da eseguire
     * @param  string  $message  Messaggio da mostrare
     *
     * @throws Exception In caso di errore nell'esecuzione
     */
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

    /**
     * Recupera il valore di una variabile dal file .env.
     *
     * @param  string  $key  Chiave della variabile
     * @param  string  $default  Valore di default se non trovata
     * @return string Valore della variabile
     */
    private function getEnvValue(string $key, string $default = ''): string
    {
        if (preg_match("/^{$key}=(.*)$/m", $this->envContent, $matches)) {
            return trim($matches[1], "\"'");
        }

        return $default;
    }

    /**
     * Aggiorna o aggiunge una variabile nel file .env.
     *
     * @param  string  $key  Chiave della variabile
     * @param  string  $value  Valore da impostare
     *
     * @throws Exception Se il file .env non esiste
     */
    private function updateEnv(string $key, string $value): void
    {
        if (! File::exists($this->envPath)) {
            throw new Exception('.env file non trovato!');
        } else {
            $this->envContent = File::get($this->envPath);
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

    /**
     * Formatta la riga da scrivere nel file .env per una variabile.
     *
     * @param  string  $key  Chiave della variabile
     * @param  string  $value  Valore da impostare
     * @return string Riga formattata
     */
    private function formatEnvValue(string $key, string $value): string
    {
        // Per specifiche chiavi DB non usiamo le virgolette
        if (in_array($key, self::DB_SPECIFIC_KEYS)) {
            return "{$key}={$value}";
        }

        return "{$key}=\"{$value}\"";
    }

    /**
     * Commenta le variabili specificate nel file .env.
     *
     * @param  array<int, string>  $keys  Elenco chiavi da commentare
     */
    private function commentEnvKeys(array $keys): void
    {
        foreach ($keys as $key) {
            $pattern = "/^{$key}=.*$/m";
            $this->envContent = preg_replace($pattern, '# $0', $this->envContent);
        }

        File::put($this->envPath, $this->envContent);
        $this->info('Commentate variabili DB non necessarie per SQLite');
    }

    /**
     * Decommenta le variabili specificate nel file .env.
     *
     * @param  array<int, string>  $keys  Elenco chiavi da decommentare
     */
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

    /**
     * Verifica se il database esiste tentando la connessione.
     *
     * @return bool True se il database esiste, false altrimenti
     */
    private function databaseExists(): bool
    {
        $this->refreshConfig();

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

    private function refreshConfig(): void
    {
        // refresh env file to ensure latest changes are applied
        $this->envContent = File::get($this->envPath);

        // set config values to ensure they are up-to-date
        config([
            'database.default' => $this->getEnvValue('DB_CONNECTION', 'mysql'),
            'database.connections.'.$this->getEnvValue('DB_CONNECTION', 'mysql').'.host' => $this->getEnvValue('DB_HOST'),
            'database.connections.'.$this->getEnvValue('DB_CONNECTION', 'mysql').'.port' => $this->getEnvValue('DB_PORT'),
            'database.connections.'.$this->getEnvValue('DB_CONNECTION', 'mysql').'.database' => $this->getEnvValue('DB_DATABASE'),
            'database.connections.'.$this->getEnvValue('DB_CONNECTION', 'mysql').'.username' => $this->getEnvValue('DB_USERNAME'),
            'database.connections.'.$this->getEnvValue('DB_CONNECTION', 'mysql').'.password' => $this->getEnvValue('DB_PASSWORD'),

            'app.default_user.email' => $this->getEnvValue('DEFAULT_USER_EMAIL'),
            'app.default_user.password' => $this->getEnvValue('DEFAULT_USER_PASSWORD'),
            'app.default_user.name' => $this->getEnvValue('DEFAULT_USER_NAME'),
        ]);

        // clear any cached config to force reload
        DB::purge();
    }
}
