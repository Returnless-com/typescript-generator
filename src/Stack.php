<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Nullable;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;
use Stringable;

final class Stack
{
    /** @var array<string> */
    private array $stack = [];

    private static ?self $instance = null;

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @return array<string>
     */
    public function getStack(): array
    {
        return $this->stack;
    }

    /**
     * @param  class-string  $className
     *
     * @throws \ReflectionException
     */
    public function add(string $className): self
    {
        // If the item is already in the stack, we don't need to add it again.
        if (in_array($className, $this->stack, true)) {
            return $this;
        }

        $this->stack[] = $className;

        $reflectionClass = new ReflectionClass($className);

        foreach ($reflectionClass->getPublicClassAttributes() as $publicClassAttribute) {
            /** @var class-string $fullyQualifiedStructuralElementName */
            $fullyQualifiedStructuralElementName = $this->getFullyQualifiedStructuralElementName(
                $publicClassAttribute->type(),
            );

            // If the attribute is an object from the App namespace, we add it to the stack.
            if ($this->shouldAddToStack($fullyQualifiedStructuralElementName)) {
                $this->add($fullyQualifiedStructuralElementName);
            }
        }

        return $this;
    }

    public function reset(): void
    {
        $this->stack = [];
    }

    /**
     * @param  class-string|null  $fullyQualifiedStructuralElementName
     *
     * @throws \ReflectionException
     */
    private function shouldAddToStack(?string $fullyQualifiedStructuralElementName): bool
    {
        if ($fullyQualifiedStructuralElementName === null) {
            return false;
        }

        $reflectionClass = new ReflectionClass($fullyQualifiedStructuralElementName);

        return ! $reflectionClass->implementsInterface(Stringable::class)
            && str_starts_with($fullyQualifiedStructuralElementName, 'App\\');
    }

    private function getFullyQualifiedStructuralElementName(Type $type): ?string
    {
        if ($type instanceof Nullable) {
            $type = $type->getActualType();
        }

        if (method_exists($type, 'getValueType')) {
            /** @var \phpDocumentor\Reflection\Types\AbstractList|\phpDocumentor\Reflection\Types\Expression $valueType */
            $valueType = $type->getValueType();

            if (method_exists($valueType, 'getFqsen')) {
                return ltrim((string) $valueType->getFqsen(), '\\');
            }
        }

        if (method_exists($type, 'getFqsen')) {
            return ltrim((string) $type->getFqsen(), '\\');
        }

        return null;
    }
}
