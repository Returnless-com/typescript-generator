<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements \Illuminate\Contracts\Support\Arrayable<string, int|string>
 */
final class DummyArrayable implements Arrayable
{
    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [];
    }
}
