<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\TypeResolvers;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionType;

abstract class AbstractTypeResolver
{
    abstract public function resolve(): ?Type;

    public function __construct(
        protected readonly ReflectionMethod|ReflectionProperty $reflectedClassAttribute,
    ) {}

    public function resolveNullType(Type $type, ?ReflectionType $reflectionType = null): Type
    {
        if ($reflectionType === null || $reflectionType->allowsNull() === false) {
            return $type;
        }

        if ($type instanceof Mixed_) {
            return $type;
        }

        if ($type instanceof Nullable) {
            return $type;
        }

        if ($type instanceof Compound && $type->contains(new Null_)) {
            return $type;
        }

        if ($type instanceof Compound) {
            return new Compound(array_merge(
                iterator_to_array($type->getIterator()),
                [new Null_],
            ));
        }

        return new Nullable($type);
    }
}
