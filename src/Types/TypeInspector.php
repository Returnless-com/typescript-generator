<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Types;

use phpDocumentor\Reflection\Type;
use Spatie\Invade\Invader;

final class TypeInspector
{
    /** @var \Spatie\Invade\Invader<\phpDocumentor\Reflection\Type> */
    private Invader $invader;

    public function __construct(Type $type)
    {
        $invader = invade($type);

        $this->invader = $invader;
    }

    public function keyType(): mixed
    {
        return $this->invader->__get('keyType');
    }

    public function valueType(): mixed
    {
        return $this->invader->__get('valueType');
    }

    public function defaultKeyType(): mixed
    {
        return $this->invader->__get('defaultKeyType');
    }
}
