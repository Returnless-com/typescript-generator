<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Iterators;

use RuntimeException;

final class Psr4AttributeIterator extends AbstractAttributeIterator
{
    /**
     * @return array<string, class-string>
     *
     * @throws \JsonException
     */
    protected function getIterable(): array
    {
        $contents = file_get_contents(base_path('composer.json'));

        if ($contents === false) {
            throw new RuntimeException('Could not read composer.json file.');
        }

        /** @var array<string, mixed> $composer */
        $composer = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        if (! isset($composer['autoload'])) {
            throw new RuntimeException('No autoload section found in composer.json file.');
        }

        /** @var array<string, array<string, class-string>> $autoload */
        $autoload = $composer['autoload'];

        return $autoload['psr-4'];
    }
}
