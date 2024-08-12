<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests;

use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\Tests\stubs\ArrayMethodStub;
use Returnless\TypescriptGenerator\TypescriptGenerator;

final class TypescriptGeneratorTest extends TestCase
{
    #[Test]
    public function it_can_compile_a_class(): void
    {
        $classCompiler = new TypescriptGenerator;

        self::assertStringStartsWith('export type ArrayMethodStub = {', $classCompiler->generate(ArrayMethodStub::class));
        self::assertStringEndsWith('};', $classCompiler->generate(ArrayMethodStub::class));
    }
}
