<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests;

use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\ClassCompiler;
use Returnless\TypescriptGenerator\Tests\stubs\ArrayMethodStub;

final class ClassCompilerTest extends TestCase
{
    #[Test]
    public function it_can_compile_a_class(): void
    {
        $classCompiler = new ClassCompiler;

        self::assertStringStartsWith('export type ArrayMethodStub = {', $classCompiler->compile(ArrayMethodStub::class));
        self::assertStringEndsWith('};', $classCompiler->compile(ArrayMethodStub::class));
    }
}
