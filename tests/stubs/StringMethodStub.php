<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

final class StringMethodStub
{
    public function stringMethodReturnType(): string
    {
        return 'test';
    }

    /**
     * @return string
     */
    public function stringMethodDocBlock()
    {
        return 'test';
    }
}
