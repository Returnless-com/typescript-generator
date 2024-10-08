<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\Transpilers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;
use Returnless\TypescriptGenerator\Tests\stubs\ArrayMethodStub;
use Returnless\TypescriptGenerator\Tests\stubs\BooleanMethodStub;
use Returnless\TypescriptGenerator\Tests\stubs\EnumMethodStub;
use Returnless\TypescriptGenerator\Tests\stubs\NumberStub;
use Returnless\TypescriptGenerator\Tests\stubs\StringMethodStub;
use Returnless\TypescriptGenerator\Tests\stubs\VoidMethodStub;
use Returnless\TypescriptGenerator\Tests\TestCase;
use Returnless\TypescriptGenerator\Transpilers\TypeTranspiler;

final class TypeTranspilerTest extends TestCase
{
    #[Test]
    #[DataProvider('provideVoidMethodStubs')]
    public function it_correctly_transpiles_types(string $stubClass, string $method, string $expected): void
    {
        $reflectionClass = new ReflectionClass($stubClass);

        $reflectedClassAttribute = new ReflectedClassAttribute($reflectionClass->getMethod($method));

        $typeTranspiler = new TypeTranspiler;

        self::assertSame(
            $expected,
            $typeTranspiler->transpile($reflectedClassAttribute->type()),
        );
    }

    #[Test]
    #[DataProvider('providePropertyStubs')]
    public function it_correctly_transpiles_property_types(string $stubClass, string $method, string $expected): void
    {
        $reflectionClass = new ReflectionClass($stubClass);

        $reflectedProperty = new ReflectedClassAttribute($reflectionClass->getProperty($method));

        $typeTranspiler = new TypeTranspiler;

        self::assertSame(
            $expected,
            $typeTranspiler->transpile($reflectedProperty->type()),
        );
    }

    /**
     * @return array[string, string, string]
     */
    public static function provideVoidMethodStubs(): array
    {
        return [
            [VoidMethodStub::class, 'voidMethodReturnType', 'unknown'],
            [VoidMethodStub::class, 'voidMethodDocBlock', 'unknown'],
            [BooleanMethodStub::class, 'booleanMethodDocBlock', 'boolean'],
            [BooleanMethodStub::class, 'booleanMethodDocBlock', 'boolean'],
            [EnumMethodStub::class, 'enumMethodDocBlock', 'TestEnum'],
            [EnumMethodStub::class, 'enumMethodDocBlock', 'TestEnum'],
            [StringMethodStub::class, 'stringMethodReturnType', 'string'],
            [StringMethodStub::class, 'stringMethodDocBlock', 'string'],
            [StringMethodStub::class, 'stringableClass', 'string'],
            [StringMethodStub::class, 'nullableString', 'string | null'],
            [ArrayMethodStub::class, 'unknownArray', 'unknown[]'],
            [ArrayMethodStub::class, 'recordArray', 'Record<number, string>'],
            [ArrayMethodStub::class, 'arrayOfType', 'DummyStub[]'],
            [ArrayMethodStub::class, 'arrayOfNativeType', 'number[]'],
            [ArrayMethodStub::class, 'arrayOfArrayable', 'Record<string, string | null>'],
            [ArrayMethodStub::class, 'arrayShape', '{ label: string, value?: number | undefined }'],
            [ArrayMethodStub::class, 'undefinedCollection', 'unknown[]'],
            [ArrayMethodStub::class, 'recordCollection', 'Record<number, string>'],
            [ArrayMethodStub::class, 'listCollection', 'string[]'],
            [ArrayMethodStub::class, 'listCollectionOfType', 'DummyStub[]'],
            [ArrayMethodStub::class, 'recordCollectionOfType', 'Record<number, DummyStub>'],
            [ArrayMethodStub::class, 'listOfString', 'string[]'],
            [ArrayMethodStub::class, 'listOfNestedType', 'Record<number, DummyStub>[]'],
            [ArrayMethodStub::class, 'untypedKeyOfType', 'Record<string | number, DummyStub>'],
            [NumberStub::class, 'intMethod', 'number'],
            [NumberStub::class, 'floatMethod', 'number'],
        ];
    }

    public static function providePropertyStubs(): array
    {
        return [
            [StringMethodStub::class, 'stringProperty', 'string'],
        ];
    }
}
