<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

final class VoidMethodStub
{
    public function voidMethodReturnType(): void {}

    /**
     * @return void
     */
    public function voidMethodDocBlock() {}
}
