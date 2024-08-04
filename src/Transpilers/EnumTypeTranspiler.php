<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Transpilers;

use ReflectionEnum;
use ReflectionEnumBackedCase;
use ReflectionEnumUnitCase;

readonly class EnumTypeTranspiler
{
    /**
     * @param  class-string<\UnitEnum>  $className
     */
    public function __construct(
        private string $className,
    ) {}

    /**
     * @throws \ReflectionException
     */
    public function transpile(): string
    {
        $reflectionEnum = new ReflectionEnum($this->className);

        $cases = $reflectionEnum->isBacked()
            ? $this->transpileBackedEnum($reflectionEnum)
            : $this->transpileUnitEnum($reflectionEnum);

        return sprintf('export enum %s = %s', $reflectionEnum->getShortName(), implode('|', $cases));
    }

    /**
     * @return string[]
     */
    private function transpileBackedEnum(ReflectionEnum $reflectionEnum): array
    {
        /** @var \ReflectionEnumBackedCase[] $cases */
        $cases = $reflectionEnum->getCases();

        return array_reduce(
            $cases,
            static function (array $carry, ReflectionEnumBackedCase $reflectionEnumCase) use ($reflectionEnum): array {
                $value = $reflectionEnumCase->getBackingValue();

                // If the backing type is a string, wrap the value in single quotes.
                if ((string) $reflectionEnum->getBackingType() === 'string') {
                    $value = sprintf('\'%s\'', $value);
                }

                $carry[] = $value;

                return $carry;
            }, [],
        );
    }

    /**
     * @return string[]
     */
    private function transpileUnitEnum(ReflectionEnum $reflectionEnum): array
    {
        return array_map(
            static function (ReflectionEnumUnitCase $reflectionEnumUnitCase): string {
                return $reflectionEnumUnitCase->getName();
            },
            $reflectionEnum->getCases(),
        );
    }
}
