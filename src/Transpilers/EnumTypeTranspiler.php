<?php

declare(strict_types=1);

namespace Returnless\TypescriptGenerator\Transpilers;

use InvalidArgumentException;
use ReflectionEnum;
use ReflectionEnumBackedCase;

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

        return sprintf('export type %s = %s', $reflectionEnum->getShortName(), implode('|', $cases));
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
        throw new InvalidArgumentException(
            sprintf('Non-backed enums are not supported for enum `%s`.', class_basename($reflectionEnum->getName())),
        );
    }
}
