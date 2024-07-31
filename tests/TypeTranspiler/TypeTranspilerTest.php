<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\TypeTranspiler;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\Reflection\ReflectedClassAttribute;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;
use Returnless\TypescriptGenerator\Tests\stubs\ArrayMethodStub;
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

    /**
     * @return array[string, string, string]
     */
    public static function provideVoidMethodStubs(): array
    {
        return [
            [VoidMethodStub::class, 'voidMethodReturnType', 'unknown'],
            [VoidMethodStub::class, 'voidMethodDocBlock', 'unknown'],
            [StringMethodStub::class, 'stringMethodReturnType', 'string'],
            [StringMethodStub::class, 'stringMethodDocBlock', 'string'],
            [ArrayMethodStub::class, 'unknownArray', 'unknown[]'],
            [ArrayMethodStub::class, 'recordArray', 'Record<number, string>'],
            [ArrayMethodStub::class, 'arrayOfType', 'DummyClass[]'],
            [ArrayMethodStub::class, 'arrayShape', '{ label: string, value: number }'],
            [ArrayMethodStub::class, 'undefinedCollection', 'unknown[]'],
            [ArrayMethodStub::class, 'recordCollection', 'Record<number, string>'],
            [ArrayMethodStub::class, 'recordCollectionOfType', 'Record<number, DummyClass>'],
            [ArrayMethodStub::class, 'listOfString', 'string[]'],
        ];
    }
}
