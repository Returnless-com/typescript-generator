<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Commands;

use Illuminate\Console\Command;
use Returnless\TypescriptGenerator\Iterators\Psr4AttributeIterator;
use Returnless\TypescriptGenerator\TypescriptGenerator;

final class GenerateTypescriptTypesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'typescript:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Typescript types for your resources.';

    /**
     * @throws \ReflectionException
     */
    public function handle(): int
    {
        $typescriptGenerator = new TypescriptGenerator(
            new Psr4AttributeIterator,
            resource_path('ts/app/Pages'),
        );

        foreach ($typescriptGenerator->generate() as $file) {
            $this->info("Generated: $file");
        }

        return self::SUCCESS;
    }
}
