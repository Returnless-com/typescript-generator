<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\TypeResolvers;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use ReflectionMethod;
use ReflectionProperty;

final class PhpDocTypeResolver extends AbstractTypeResolver
{
    public function resolve(): ?Type
    {
        $docComment = $this->reflectedClassAttribute->getDocComment();

        if (! $docComment) {
            return null;
        }

        preg_match(
            $this->getDocCommentRegex(),
            $docComment,
            $matches,
        );

        $docCommentDefinition = $matches[1] ?? null;

        if ($docCommentDefinition === null) {
            return null;
        }

        $resolvedType = (new TypeResolver)->resolve(
            $docCommentDefinition,
            (new ContextFactory)->createFromReflector($this->reflectedClassAttribute),
        );

        return $this->resolveNullType($resolvedType);
    }

    private function getDocCommentRegex(): string
    {
        return match (true) {
            $this->reflectedClassAttribute instanceof ReflectionMethod => '/@return ((?:\s?[|\w?|\\\\<>,-{}]+(?:\[])?)+)/',
            $this->reflectedClassAttribute instanceof ReflectionProperty => '/@var ((?:\s?[\\w?|\\\\<>,-]+(?:\[])?)+)/',
        };
    }
}
