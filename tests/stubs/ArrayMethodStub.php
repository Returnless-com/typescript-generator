<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Tests\stubs;

use Illuminate\Support\Collection;

final class ArrayMethodStub
{
    public function unknownArray(): array
    {
        // This should return `unknown[]` in the generated typescript
        return [];
    }

    /**
     * @return array<int, string>
     */
    public function recordArray(): array
    {
        // This should return `Record<number, string>` in the generated typescript
        return [];
    }

    /**
     * @return \Returnless\TypescriptGenerator\Tests\stubs\DummyStub[]
     */
    public function arrayOfType(): array
    {
        // This should return `DummyStub[]` in the generated typescript
        return [];
    }

    /**
     * @return array{label: string, value: int}
     */
    public function arrayShape(): array
    {
        // This should return `{ label: string, value: number }` in the generated typescript
        return [
            'label' => 'test',
            'value' => 1,
        ];
    }

    public function undefinedCollection(): Collection
    {
        // This should return `unknown[]` in the generated typescript
        return collect();
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    public function recordCollection(): Collection
    {
        // This should return `Record<int, string>` in the generated typescript
        return collect();
    }

    /**
     * @return \Illuminate\Support\Collection<array-key, string>
     */
    public function listCollection(): Collection
    {
        return Collection::make();
    }

    /**
     * @return \Illuminate\Support\Collection<array-key, \Returnless\TypescriptGenerator\Tests\stubs\DummyStub>
     */
    public function listCollectionOfType(): Collection
    {
        return Collection::make();
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Returnless\TypescriptGenerator\Tests\stubs\DummyStub>
     */
    public function recordCollectionOfType(): Collection
    {
        // This should return `Record<int, DummyClass>` in the generated typescript
        return collect();
    }

    /**
     * @return list<string>
     */
    public function listOfString(): array
    {
        // This should return `string[]` in the generated typescript
        return ['test'];
    }

    /**
     * @return list<\Illuminate\Support\Collection<int, \Returnless\TypescriptGenerator\Tests\stubs\DummyStub>>
     */
    public function listOfNestedType(): array
    {
        return [];
    }

    /**
     * @return \Illuminate\Support\Collection<\Returnless\TypescriptGenerator\Tests\stubs\DummyStub>
     */
    public function untypedKeyOfType(): array
    {
        return [];
    }
}
