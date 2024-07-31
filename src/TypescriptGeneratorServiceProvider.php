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
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\GenerateTypescriptTypesCommand::class,
            ]);
        }
    }
}
