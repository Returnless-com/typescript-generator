<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Reflection;

use ReflectionClass as BaseReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @template T of object
 *
 * @extends \ReflectionClass<T>
 */
final class ReflectionClass extends BaseReflectionClass
{
    /**
     * @return \Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute[]
     */
    public function getPublicClassAttributes(): array
    {
        return array_merge(
            $this->getPublicProperties(),
            $this->getPublicMethods(),
        );
    }

    /**
     * @return \Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute[]
     */
    private function getPublicMethods(): array
    {
        $publicMethods = array_filter(
            $this->getMethods(ReflectionMethod::IS_PUBLIC),
            function (ReflectionMethod $reflectionMethod): bool {
                // Filter out all methods coming directly from the
                // given class and exclude magic methods.
                return $reflectionMethod->class === $this->getName()
                    && ! str_starts_with($reflectionMethod->getName(), '__');
            },
        );

        return array_map(
            static fn (ReflectionMethod $reflectionMethod) => new ReflectedClassAttribute($reflectionMethod),
            $publicMethods,
        );
    }

    /**
     * @return \Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute[]
     */
    private function getPublicProperties(): array
    {
        return array_map(
            static fn (ReflectionProperty $reflectionProperty) => new ReflectedClassAttribute($reflectionProperty),
            $this->getProperties(ReflectionProperty::IS_PUBLIC),
        );
    }
}
