<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\Transpilers;

use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\Tests\stubs\BackedEnumStub;
use Returnless\TypescriptGenerator\Tests\stubs\NonBackedEnumStub;
use Returnless\TypescriptGenerator\Tests\TestCase;
use Returnless\TypescriptGenerator\Transpilers\EnumTypeTranspiler;

final class EnumTypeTranspilerTest extends TestCase
{
    #[Test]
    public function it_can_transpile_a_backed_enum(): void
    {
        $enumTypeTranspiler = new EnumTypeTranspiler(BackedEnumStub::class);

        self::assertSame('export enum BackedEnumStub = \'foo\'|\'bar\'|\'baz\'', $enumTypeTranspiler->transpile());
    }

    #[Test]
    public function it_can_transpile_a_non_backed_enum(): void
    {
        $enumTypeTranspiler = new EnumTypeTranspiler(NonBackedEnumStub::class);

        self::assertSame('export enum NonBackedEnumStub = FOO|BAR|BAZ', $enumTypeTranspiler->transpile());
    }
}
