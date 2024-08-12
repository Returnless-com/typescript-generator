<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\TypeResolvers;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\ContextFactory;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;

final class ReflectionTypeResolver extends AbstractTypeResolver
{
    public function resolve(): ?Type
    {
        $reflectionType = $this->getReflectionType();

        if ($reflectionType === null) {
            return null;
        }

        $typeResolver = new TypeResolver;

        return match (true) {
            $reflectionType instanceof ReflectionNamedType => $this->resolveNamedType($reflectionType, $typeResolver),
            $reflectionType instanceof ReflectionUnionType => $this->resolveUnionType($reflectionType, $typeResolver),
            default => null,
        };
    }

    private function getReflectionType(): ?ReflectionType
    {
        return match (true) {
            $this->reflectedClassAttribute instanceof ReflectionMethod => $this->reflectedClassAttribute->getReturnType(),
            $this->reflectedClassAttribute instanceof ReflectionProperty => $this->reflectedClassAttribute->getType(),
        };
    }

    private function resolveNamedType(ReflectionNamedType $reflectionType, TypeResolver $typeResolver): Type
    {
        return $this->resolveNullType(
            $typeResolver->resolve(
                $this->resolveName($reflectionType),
                (new ContextFactory)->createFromReflector($this->reflectedClassAttribute),
            ),
            $reflectionType,
        );
    }

    private function resolveUnionType(ReflectionUnionType $reflectionType, TypeResolver $typeResolver): Type
    {
        $types = array_filter(
            $reflectionType->getTypes(),
            static fn (ReflectionNamedType|ReflectionIntersectionType $reflectionType) => $reflectionType instanceof ReflectionNamedType,
        );

        $compoundType = new Compound(array_map(
            function (ReflectionIntersectionType|ReflectionNamedType $reflectionType) use ($typeResolver) {
                return $typeResolver->resolve($this->resolveName($reflectionType));
            },
            $types,
        ));

        return $this->resolveNullType($compoundType);
    }

    private function resolveUnionType(ReflectionUnionType $reflectionType, TypeResolver $typeResolver): Type
    {
        $types = array_filter(
            $reflectionType->getTypes(),
            static fn (ReflectionNamedType|ReflectionIntersectionType $reflectionType) => $reflectionType instanceof ReflectionNamedType,
        );

        $compoundType = new Compound(array_map(
            static function (ReflectionIntersectionType|ReflectionNamedType $reflectionType) use ($typeResolver) {
                return $typeResolver->resolve(
                    $reflectionType->getName(),
                );
            },
            $types,
        ));

        return $this->resolveNullType($compoundType);
    }
}
