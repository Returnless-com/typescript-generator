<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

final class BooleanMethodStub
{
    public function booleanMethodReturnType(): bool {}

    /**
     * @return bool
     */
    public function booleanMethodDocBlock() {}
}
