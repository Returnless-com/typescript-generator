<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

enum TestEnum: string
{
    case A = 'a';
    case B = 'b';
}

class EnumMethodStub
{
    public function enumMethodReturnType(): TestEnum {}

    /**
     * @return TestEnum
     */
    public function enumMethodDocBlock() {}
}
