<?php

namespace AndKom;

interface DecimalInterface
{
    const RE_NUMBER = '/^[+-]?\d*(\.\d+)?$/';
    const RE_NUMBER_EXP = '/^[+-]?\d*(\.\d+)?(e[\+\-]?\d+)?$/';

    const ROUND_UP = 1;
    const ROUND_DOWN = 2;
    const ROUND_HALF_UP = 3;
    const ROUND_HALF_DOWN = 4;
    const ROUND_HALF_EVEN = 5;
    const ROUND_HALF_ODD = 6;

    public static function setDefaultScale(int $scale = null);

    public static function getDefaultScale(): int;

    public static function create($value = null, int $scale = null): self;

    public static function createFromFloat(float $value, int $scale = null): self;

    public static function createFromInteger(int $value, int $scale = null): self;

    public static function createFromString(string $value, int $scale = null): self;

    public static function createFromDecimal(self $decimal, int $scale = null): self;

    public static function max(...$args): self;

    public static function min(...$args): self;

    public function getValue(): string;

    public function setValue(string $value): self;

    public function getScale(): int;

    public function setScale(int $scale = null): self;

    public function add($value, $scale = null): self;

    public function subtract($value, $scale = null): self;

    public function multiply($value, $scale = null): self;

    public function divide($value, $scale = null): self;

    public function modulus($value): self;

    public function power($value, $scale = null): self;

    public function squareRoot($scale = null): self;

    public function truncate(int $precision = 0): self;

    public function round(int $precision = 0, int $mode = self::ROUND_HALF_UP): self;

    public function roundUp(int $precision = 0): self;

    public function roundDown(int $precision = 0): self;

    public function roundHalfUp(int $precision = 0): self;

    public function roundHalfDown(int $precision = 0): self;

    public function roundHalfEven(int $precision = 0): self;

    public function roundHalfOdd(int $precision = 0): self;

    public function floor(): self;

    public function ceil(): self;

    public function inverse(): self;

    public function negate(): self;

    public function absolutize(): self;

    public function toFloat(): float;

    public function toInt(): int;

    public function toString(bool $trailingZeros = false): string;

    public function toDecimal(): self;

    public function toMutable(): self;

    public function toImmutable(): self;

    public function toDigits(int $digits): self;

    public function toScientific(int $precision = 5, string $exponent = 'E'): string;

    public function toFormat(int $precision = 0, string $decPoint = '.', string $thousandsSep = ',', bool $trailingZeros = true, int $mode = self::ROUND_HALF_UP): string;

    public function toFixed(int $precision = 0): string;

    public function compareTo($arg, int $scale = null): int;

    public function isEqual($arg, int $scale = null): bool;

    public function isNotEqual($arg, int $scale = null): bool;

    public function isGreaterThan($arg, int $scale = null): bool;

    public function isGreaterThanOrEquals($arg, int $scale = null): bool;

    public function isLessThan($arg, int $scale = null): bool;

    public function isLessThanOrEquals($arg, int $scale = null): bool;

    public function isInteger(): bool;

    public function isDecimal(): bool;

    public function isNegative(): bool;

    public function isPositive(): bool;

    public function isZero(): bool;

    public function isNotZero(): bool;

    public function isEven(): bool;

    public function isOdd(): bool;
}