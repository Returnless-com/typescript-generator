<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator;

use Generator;
use Illuminate\Support\Facades\File;
use Returnless\TypescriptGenerator\Iterators\AbstractAttributeIterator;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;

final readonly class TypescriptGenerator
{
    public function __construct(
        private AbstractAttributeIterator $attributeIterator,
        private string $outputPath,
    ) {}

    /**
     * @return \Generator<class-string>
     *
     * @throws \ReflectionException
     */
    public function generate(): Generator
    {
        $classCompiler = new ClassCompiler;

        foreach ($this->attributeIterator as $typescriptAttribute) {
            $this->writeStackToFile($typescriptAttribute, $classCompiler->compile($typescriptAttribute));

            yield $typescriptAttribute;
        }
    }

    /**
     * @param  class-string  $typescriptAttribute
     *
     * @throws \ReflectionException
     */
    private function getPath(string $typescriptAttribute): string
    {
        $reflectionClass = new ReflectionClass($typescriptAttribute);

        return $reflectionClass->getProperty('viewPath')->getDefaultValue() . '/types.ts';
    }

    /**
     * @param  class-string  $typescriptAttribute
     *
     * @throws \ReflectionException
     */
    private function writeStackToFile(string $typescriptAttribute, string $generatedClassTypes): void
    {
        $resourcePath = $this->getPath($typescriptAttribute);

        if (str_contains($resourcePath, '::')) {
            [$module, $path] = explode('::', $resourcePath);

            $path = sprintf('%s/%s/%s', $module, config('typescript-generator.page_path'), $path);
        } else {
            $path = sprintf('%s/%s', config('typescript-generator.page_path'), $resourcePath);
        }

        $path = $this->outputPath . '/' . $path;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $generatedClassTypes);
    }
}
