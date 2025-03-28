<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

class SimpleMenuManagerCommand extends Command
{
    public $signature = 'make:menu-handler {name} {panel?}';

    public $description = 'Create a new menu handler';

    /**
     * Filesystem instance
     */
    protected Filesystem $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (! $this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");

            $this->info("Don't forget to add the handler to the config file");
            // line to add to the config file
            $config_line = "'".Str::snake($this->argument('name'))."'".' => App\\Filament\\'.($this->argument('panel') ? ucfirst($this->argument('panel')).'\\' : '').'SimpleMenu\\Handlers\\'.$this->getSingularClassName($this->argument('name')).'Handler::class,';
            $this->info($config_line);
        } else {
            $this->warn("File : {$path} already exits");
        }
    }

    /**
     * Return the stub file path
     */
    public function getStubPath(): string
    {
        return __DIR__.'/../../stubs/handler.stub';
    }

    /**
     **
     * Map the stub variables present in stub to its value
     */
    public function getStubVariables(): array
    {
        return [
            'TITLE' => $this->getTitleFromClassName($this->argument('name')),
            'PANEL' => $this->argument('panel') ? ucfirst($this->argument('panel')).'\\' : '',
            'CLASS_NAME' => $this->getSingularClassName($this->argument('name')),
        ];
    }

    /**
     * Get the stub path and the stub variables
     */
    public function getSourceFile(): string|array|bool
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    /**
     * Replace the stub variables(key) with the desire value
     */
    public function getStubContents(string $stub, array $stubVariables = []): string|array|bool
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$'.$search.'$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of the generated class.
     */
    public function getSourceFilePath(): string
    {
        $panel = $this->argument('panel');
        $panelPrefix = $panel ? ucfirst($panel).'\\' : '';

        $path = base_path('app\\Filament\\'.$panelPrefix.'SimpleMenu').'\\Handlers\\'.$this->getSingularClassName($this->argument('name')).'Handler.php';

        return str_replace('\\', '/', $path);
    }

    /**
     * Return the Singular Capitalize Name
     */
    public function getSingularClassName($name): string
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getTitleFromClassName($name): string
    {
        $singular = $this->getSingularClassName($name);

        return Str::title(Str::snake($singular, ' '));
    }

    /**
     * Build the directory for the class if necessary.
     */
    protected function makeDirectory(string $path): string
    {

        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
