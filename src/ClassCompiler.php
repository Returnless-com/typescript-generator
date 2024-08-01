<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator;

use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Type;
use Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;
use Returnless\TypescriptGenerator\Transpilers\EnumTypeTranspiler;
use Returnless\TypescriptGenerator\Transpilers\TypeTranspiler;

final class ClassCompiler
{
    /**
     * @param  class-string  $className
     *
     * @throws \ReflectionException
     */
    public function compile(string $className): string
    {
        Stack::getInstance()->reset();
        Stack::getInstance()->add($className);

        $generatedClassTypes = array_map(function (string $className) {
            /** @var class-string $className */
            return $this->generateClassTypes($className);
        }, Stack::getInstance()->getStack());

        return implode("\n", $generatedClassTypes);
    }

    /**
     * @param  class-string  $className
     *
     * @throws \ReflectionException
     */
    private function generateClassTypes(string $className): string
    {
        $reflectionClass = new ReflectionClass($className);

        /** @var array<string, \phpDocumentor\Reflection\Type> $types */
        $types = Arr::mapWithKeys(
            $reflectionClass->getPublicClassAttributes(),
            static fn (ReflectedClassAttribute $reflectedClassAttribute) => [
                $reflectedClassAttribute->name() => $reflectedClassAttribute->type(),
            ],
        );

        $transpiledTypes = Arr::mapWithKeys($types, static fn (Type $type, string $name) => [
            $name => (new TypeTranspiler)->transpile($type),
        ]);

        if ($reflectionClass->isEnum()) {
            /** @var class-string<\UnitEnum|\BackedEnum> $enumClassName */
            $enumClassName = $className;

            return (new EnumTypeTranspiler($enumClassName))->transpile();
        }

        return $this->compileClassString($reflectionClass, $transpiledTypes);
    }

    /**
     * @param  \Returnless\TypescriptGenerator\Reflection\ReflectionClass<object>  $reflectionClass
     * @param  array<string, string>  $transpiledTypes
     */
    private function compileClassString(ReflectionClass $reflectionClass, array $transpiledTypes): string
    {
        return sprintf(
            'export type %s = {%s}',
            $reflectionClass->getShortName(),
            implode(' ', Arr::mapWithKeys($transpiledTypes, static fn ($type, $name) => [
                $name => sprintf('%s: %s;', $name, $type),
            ])),
        );
    }
}
