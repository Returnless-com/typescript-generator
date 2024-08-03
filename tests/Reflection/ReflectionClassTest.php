<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\Reflection;

use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\Reflection\ReflectionClass;
use Returnless\TypescriptGenerator\Tests\TestCase;

final class ReflectionClassTest extends TestCase
{
    #[Test]
    public function it_can_get_the_public_class_attributes(): void
    {
        $dummyClass = new class
        {
            public string $name = 'John Doe';

            public int $age = 30;

            public function testMethod(): string
            {
                return 'test';
            }
        };

        $reflectionClass = new ReflectionClass($dummyClass);

        self::assertCount(3, $reflectionClass->getPublicClassAttributes());
    }
}
