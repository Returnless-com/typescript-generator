<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests;

use PHPUnit\Framework\Attributes\Test;
use Returnless\TypescriptGenerator\Stack;

final class StackTest extends TestCase
{
    #[Test]
    public function it_can_add_to_the_stack(): void
    {
        $stack = Stack::getInstance();

        $stack->add(self::class);

        self::assertCount(1, $stack->getStack());
    }

    #[Test]
    public function it_wont_add_one_class_multiple_times(): void
    {
        $stack = Stack::getInstance();

        $stack->add(self::class);
        $stack->add(self::class);

        self::assertCount(1, $stack->getStack());
    }

    #[Test]
    public function it_can_reset_the_stack(): void
    {
        $stack = Stack::getInstance();

        $stack->add(self::class);

        self::assertNotEmpty($stack->getStack());

        $stack->reset();

        self::assertEmpty($stack->getStack());
    }
}
