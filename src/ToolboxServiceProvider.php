<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox;

use Illuminate\Support\ServiceProvider;

class ToolboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/toolbox.php', 'toolbox');
    }

    public function boot(): void
    {
        $this->commands([
            MakeActionCommand::class,
        ]);
        
        $this->publishes([
            __DIR__.'/../config/toolbox.php' => config_path('toolbox.php'),
        ], 'toolbox-config');
    }
}
