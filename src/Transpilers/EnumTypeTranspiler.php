<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Transpilers;

use ReflectionEnum;
use ReflectionEnumBackedCase;

class EnumTypeTranspiler
{
    /**
     * @param  class-string<\BackedEnum|\UnitEnum>  $className
     */
    public function __construct(
        private readonly string $className,
    ) {}

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
        return $reflectionEnum->getCases();
    }
}
