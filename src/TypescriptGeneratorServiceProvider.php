<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator;

use Illuminate\Support\ServiceProvider;

final class TypescriptGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/typescript-generator.php' => config_path('typescript-generator.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/typescript-generator.php',
            'typescript-generator',
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\GenerateTypescriptTypesCommand::class,
            ]);
        }
    }
}
