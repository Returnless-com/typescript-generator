<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Reflection;

use phpDocumentor\Reflection\Type;
use ReflectionMethod;
use ReflectionProperty;
use Returnless\TypescriptGenerator\TypeResolvers\PhpDocTypeResolver;
use Returnless\TypescriptGenerator\TypeResolvers\ReflectionTypeResolver;
use Returnless\TypescriptGenerator\Types\TypescriptType;

final readonly class ReflectedClassAttribute
{
    public function __construct(
        private ReflectionMethod|ReflectionProperty $classAttribute,
    ) {}

    public function name(): string
    {
        return $this->classAttribute->getName();
    }

    public function type(): Type
    {
        /** @var \Returnless\TypescriptGenerator\TypeResolvers\AbstractTypeResolver[] $typeResolvers */
        $typeResolvers = [
            PhpDocTypeResolver::class,
            ReflectionTypeResolver::class,
        ];

        foreach ($typeResolvers as $typeResolverClassName) {
            $typeResolver = new $typeResolverClassName($this->classAttribute);

            $type = $typeResolver->resolve();

            if ($type !== null) {
                return $type;
            }
        }

        return TypescriptType::any();
    }
}
