<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Types;

use phpDocumentor\Reflection\Type;

final readonly class TypescriptType implements Type
{
    public function __construct(
        private string $typescript,
    ) {}

    public function __toString(): string
    {
        return $this->typescript;
    }

    public static function any(): TypescriptType
    {
        return new self('any');
    }
}
