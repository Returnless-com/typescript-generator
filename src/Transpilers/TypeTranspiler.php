<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Transpilers;

use phpDocumentor\Reflection\PseudoTypes\ArrayShape;
use phpDocumentor\Reflection\PseudoTypes\ArrayShapeItem;
use phpDocumentor\Reflection\PseudoTypes\List_;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;
use Returnless\TypescriptGenerator\Types\TypeInspector;
use Returnless\TypescriptGenerator\Types\TypescriptType;
use Stringable;

final class TypeTranspiler
{
    private const string TYPE_UNKNOWN = 'unknown';

    /**
     * @throws \ReflectionException
     */
    public function transpile(Type $type): string
    {
        $result = match (true) {
            $type instanceof String_ => 'string',
            $type instanceof Boolean => 'boolean',
            $type instanceof Integer, $type instanceof Float_ => 'number',
            $type instanceof Array_ => $this->resolveArrayType($type),
            $type instanceof Object_ => $this->resolveObjectType($type),
            $type instanceof Collection => $this->resolveCollectionType($type),
            $type instanceof Compound => $this->resolveCompoundType($type),
            $type instanceof ArrayShape => $this->resolveArrayShapeType($type),
            $type instanceof Nullable => $this->resolveNullableType($type),
            $type instanceof TypeScriptType => (string) $type,
            default => null,
        };

        return $result ?? self::TYPE_UNKNOWN;
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveArrayType(Array_ $type): string
    {
        $typeInspector = new TypeInspector($type);

        if ($type instanceof List_ || $type->getValueType() instanceof Object_) {
            return $this->arrayOf($this->transpile($type->getValueType()));
        }

        if ($typeInspector->keyType() !== null && $typeInspector->defaultKeyType() instanceof Compound) {
            return $this->resolveRecordType($type);
        }

        return $this->arrayOf(self::TYPE_UNKNOWN);
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveRecordType(AbstractList $type): string
    {
        return sprintf(
            'Record<%s, %s>',
            $this->transpile($type->getKeyType()),
            $this->transpile($type->getValueType()),
        );
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveArrayShapeType(ArrayShape $type): string
    {
        $arrayShapeItems = array_map(
            fn (ArrayShapeItem $arrayShapeItem) => $arrayShapeItem->getKey() . ': ' . $this->transpile($arrayShapeItem->getValue()),
            $type->getItems(),
        );

        return sprintf('{ %s }', implode(', ', $arrayShapeItems));
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveObjectType(Object_ $type): string
    {
        $fullyQualifiedStructuralElementName = $type->getFqsen();

        // If the fully qualified name is null, or the class-name is Collection,
        // we have to assume it's an array of unknown objects.
        if ($fullyQualifiedStructuralElementName === null || $fullyQualifiedStructuralElementName->getName() === 'Collection') {
            return $this->arrayOf(self::TYPE_UNKNOWN);
        }

        return $fullyQualifiedStructuralElementName->getName();
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveNullableType(Nullable $type): string
    {
        return "{$this->transpile($type->getActualType())} | null";
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveCollectionType(Collection $type): string
    {
        $valueType = $type->getValueType();

        if (! $valueType instanceof Object_ || ! $type->getKeyType() instanceof Object_) {
            return $this->resolveRecordType($type);
        }

        return $this->arrayOf($this->resolveObjectType($valueType));
    }

    /**
     * @throws \ReflectionException
     */
    private function resolveCompoundType(Compound $type): string
    {
        $transformed = array_map(
            fn (Type $type) => $this->transpile($type),
            iterator_to_array($type->getIterator()),
        );

        return implode(' & ', array_unique($transformed));
    }

    private function arrayOf(string $type): string
    {
        return $type . '[]';
    }
}
