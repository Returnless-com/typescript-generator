<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

final class StringMethodStub
{
    public string $stringProperty;

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

    public function nullableString(): ?string
    {
        return null;
    }

    public function stringableClass(): StringableClass
    {
        return new StringableClass;
    }
}
