<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\Reflection;

use phpDocumentor\Reflection\PseudoTypes\ArrayShape;
use phpDocumentor\Reflection\PseudoTypes\ArrayShapeItem;
use phpDocumentor\Reflection\PseudoTypes\List_;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\Void_;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute;
use Returnless\TypescriptGenerator\Tests\stubs\ArrayMethodStub;
use Returnless\TypescriptGenerator\Tests\stubs\StringMethodStub;
use Returnless\TypescriptGenerator\Tests\stubs\VoidMethodStub;
use Returnless\TypescriptGenerator\Tests\TestCase;
use Returnless\TypescriptGenerator\Types\TypeInspector;

final class ReflectionClassAttributeTest extends TestCase
{
    #[Test]
    public function it_can_get_the_name_of_a_reflected_class_attribute(): void
    {
        $reflectionClass = new ReflectionClass(VoidMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('voidMethodReturnType'));

        self::assertSame('voidMethodReturnType', $reflectedClassAttribute->name());
    }

    #[Test]
    public function it_can_reflect_void_method_return_types(): void
    {
        $reflectionClass = new ReflectionClass(VoidMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('voidMethodReturnType'));

        self::assertInstanceOf(Void_::class, $reflectedClassAttribute->type());
    }

    #[Test]
    public function it_can_reflect_void_method_docblock(): void
    {
        $reflectionClass = new ReflectionClass(VoidMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('voidMethodDocBlock'));

        self::assertInstanceOf(Void_::class, $reflectedClassAttribute->type());
    }

    #[Test]
    public function it_can_reflect_string_method_return_types(): void
    {
        $reflectionClass = new ReflectionClass(StringMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('stringMethodReturnType'));

        self::assertInstanceOf(String_::class, $reflectedClassAttribute->type());
    }

    #[Test]
    public function it_can_reflect_string_method_docblock(): void
    {
        $reflectionClass = new ReflectionClass(StringMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('stringMethodDocBlock'));

        self::assertInstanceOf(String_::class, $reflectedClassAttribute->type());
    }

    #[Test]
    public function it_can_reflect_unknown_array(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('unknownArray'));

        self::assertInstanceOf(Array_::class, $reflectedClassAttribute->type());

        /** @var \phpDocumentor\Reflection\Types\Array_ $type */
        $type = $reflectedClassAttribute->type();

        $typeInspector = new TypeInspector($type);

        self::assertNull($typeInspector->keyType());
        self::assertInstanceOf(Mixed_::class, $type->getValueType());
    }

    #[Test]
    public function it_can_reflect_record_array(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('recordArray'));

        self::assertInstanceOf(Array_::class, $reflectedClassAttribute->type());

        /** @var \phpDocumentor\Reflection\Types\Array_ $type */
        $type = $reflectedClassAttribute->type();

        $typeInspector = new TypeInspector($type);

        self::assertInstanceOf(Integer::class, $typeInspector->keyType());
        self::assertInstanceOf(String_::class, $typeInspector->valueType());
        self::assertInstanceOf(Compound::class, $typeInspector->defaultKeyType());
    }

    #[Test]
    public function it_can_reflect_array_of_type(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('arrayOfType'));

        self::assertInstanceOf(Array_::class, $reflectedClassAttribute->type());

        /** @var \phpDocumentor\Reflection\Types\Array_ $type */
        $type = $reflectedClassAttribute->type();

        $typeInspector = new TypeInspector($type);

        self::assertNull($typeInspector->keyType());
        self::assertInstanceOf(Object_::class, $typeInspector->valueType());
        self::assertInstanceOf(Compound::class, $typeInspector->defaultKeyType());
    }

    #[Test]
    public function it_can_reflect_array_shape(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('arrayShape'));

        /** @var \phpDocumentor\Reflection\PseudoTypes\ArrayShape $type */
        $type = $reflectedClassAttribute->type();

        self::assertInstanceOf(ArrayShape::class, $type);

        $arrayShapeItem1 = $type->getItems()[0];
        $arrayShapeItem2 = $type->getItems()[1];

        self::assertInstanceOf(ArrayShapeItem::class, $arrayShapeItem1);
        self::assertSame('label', $arrayShapeItem1->getKey());
        self::assertInstanceOf(String_::class, $arrayShapeItem1->getValue());

        self::assertInstanceOf(ArrayShapeItem::class, $arrayShapeItem2);
        self::assertSame('value', $arrayShapeItem2->getKey());
        self::assertInstanceOf(Integer::class, $arrayShapeItem2->getValue());
    }

    #[Test]
    public function it_can_reflect_undefined_collection(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('undefinedCollection'));

        /** @var \phpDocumentor\Reflection\Types\Object_ $type */
        $type = $reflectedClassAttribute->type();

        self::assertInstanceOf(Object_::class, $type);
        self::assertSame('Collection', $type->getFqsen()->getName());
    }

    #[Test]
    public function it_can_reflect_record_collection(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('recordCollection'));

        /** @var \phpDocumentor\Reflection\Types\Collection $type */
        $type = $reflectedClassAttribute->type();

        self::assertInstanceOf(Collection::class, $type);
        self::assertInstanceOf(Integer::class, $type->getKeyType());
        self::assertInstanceOf(String_::class, $type->getValueType());
    }

    #[Test]
    public function it_can_reflect_record_collection_of_type(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('recordCollectionOfType'));

        /** @var \phpDocumentor\Reflection\Types\Collection $type */
        $type = $reflectedClassAttribute->type();

        $typeInspector = new TypeInspector($type);

        self::assertInstanceOf(Collection::class, $type);
        self::assertInstanceOf(Integer::class, $type->getKeyType());
        self::assertInstanceOf(Object_::class, $typeInspector->valueType());
        self::assertInstanceOf(Compound::class, $typeInspector->defaultKeyType());
    }

    #[Test]
    public function it_can_reflect_list_of_string(): void
    {
        $reflectionClass = new ReflectionClass(ArrayMethodStub::class);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod('listOfString'));

        /** @var \phpDocumentor\Reflection\PseudoTypes\List_ $type */
        $type = $reflectedClassAttribute->type();

        self::assertInstanceOf(List_::class, $type);
        self::assertInstanceOf(String_::class, $type->getValueType());
    }
}
