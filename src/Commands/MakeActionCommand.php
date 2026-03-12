<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeActionCommand extends Command
{
    protected $signature = 'make:action {name}';

    protected $description = 'Create a new action class';

    public function __construct(private readonly Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = trim((string) $this->argument('name'));
        $name = str_replace('\\', '/', $name);
        $name = trim($name, '/');

        if ($name === '') {
            $this->error('The name argument is required.');

            return self::FAILURE;
        }

        $segments = array_values(array_filter(explode('/', $name)));
        $className = array_pop($segments);

        if ($className === null || $className === '') {
            $this->error('Invalid action name.');

            return self::FAILURE;
        }

        $relativeDirectory = 'Actions';

        if ($segments !== []) {
            $relativeDirectory .= '/'.implode('/', $segments);
        }

        $directoryPath = app_path($relativeDirectory);
        $filePath = $directoryPath.'/'.$className.'.php';

        if ($this->files->exists($filePath)) {
            $this->error('Action already exists: '.$filePath);

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists($directoryPath);

        $namespace = 'App\\Actions';

        if ($segments !== []) {
            $namespace .= '\\'.implode('\\', $segments);
        }

        $stubPath = __DIR__.'/../../stubs/action.stub';
        $stub = $this->files->get($stubPath);
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub,
        );

        $this->files->put($filePath, $content);

        $this->info('Action created successfully: '.$filePath);

        return self::SUCCESS;
    }
}
