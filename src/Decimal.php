<?php

namespace AndKom;

class Decimal implements DecimalInterface
{
    protected $value;
    protected $scale;

    protected static $defaultScale;

    public function __construct(string $value = null, int $scale = null)
    {
        $this->setScale($scale);
        $this->setValue($value);
    }

    public static function setDefaultScale(int $scale = null)
    {
        static::$defaultScale = $scale;
    }

    public static function getDefaultScale(): int
    {
        return static::$defaultScale;
    }

    public static function create($value = null, int $scale = null): DecimalInterface
    {
        if (is_float($value)) {
            return static::createFromFloat($value, $scale);
        } elseif (is_int($value)) {
            return static::createFromInteger($value, $scale);
        } elseif (is_string($value)) {
            return static::createFromString($value, $scale);
        } elseif ($value instanceof static) {
            return static::createFromDecimal($value, $scale);
        } else {
            return static::createFromString((string)$value);
        }
    }

    public static function createFromFloat(float $value, int $scale = null): DecimalInterface
    {
        $value = number_format($value, $scale, '.', '');

        return new static($value, $scale);
    }

    public static function createFromInteger(int $value, int $scale = null): DecimalInterface
    {
        return new static((string)$value, $scale);
    }

    public static function createFromString(string $value, int $scale = null): DecimalInterface
    {
        $value = strtolower($value);

        // check format
        if (!preg_match(static::RE_NUMBER_EXP, $value)) {
            throw new \InvalidArgumentException("Invalid number: $value.");
        }

        // scientific notation
        if (strpos($value, 'e') !== false) {
            list($man, $expStr) = explode('e', $value);
            $exp = (int)$expStr;

            $sign = '';
            if ($man[0] === '-' || $man[0] === '+') {
                $sign = $man[0] === '-' ? '-' : '';
                $man = substr($man, 1);
            }

            $parts = explode('.', $man);
            $intPart = $parts[0];
            $fracPart = isset($parts[1]) ? $parts[1] : '';
            $digits = $intPart . $fracPart;
            $dotPos = strlen($intPart) + $exp;

            if ($dotPos >= strlen($digits)) {
                $value = $sign . $digits . str_repeat('0', $dotPos - strlen($digits));
            } elseif ($dotPos <= 0) {
                $value = $sign . '0.' . str_repeat('0', -$dotPos) . $digits;
            } else {
                $value = $sign . substr($digits, 0, $dotPos) . '.' . substr($digits, $dotPos);
            }

            if (strpos($value, '.') !== false) {
                $value = rtrim(rtrim($value, '0'), '.');
            }

            if ($scale === null) {
                $resultParts = explode('.', $value);
                $scale = isset($resultParts[1]) ? strlen($resultParts[1]) : 0;
            }
        }

        return new static($value, $scale);
    }

    public static function createFromDecimal(DecimalInterface $decimal, int $scale = null): DecimalInterface
    {
        return new static($decimal->getValue(), $scale ?: $decimal->getScale());
    }

    public static function max(...$args): DecimalInterface
    {
        if (!count($args)) {
            throw new \InvalidArgumentException('Too few arguments.');
        }

        $max = new static(reset($args));

        foreach ($args as $arg) {
            $decimal = new static($arg);

            if ($decimal->isGreaterThan($max)) {
                $max = $decimal;
            }
        }

        return $max;
    }

    public static function min(...$args): DecimalInterface
    {
        if (!count($args)) {
            throw new \InvalidArgumentException('Too few arguments.');
        }

        $min = new static(reset($args));

        foreach ($args as $arg) {
            $decimal = new static($arg);

            if ($decimal->isLessThan($min)) {
                $min = $decimal;
            }
        }

        return $min;
    }

    protected function mutate(): DecimalInterface
    {
        return $this;
    }

    private static function incrementAtPrecision(int $precision): string
    {
        if ($precision <= 0) {
            return bcpow('10', (string)(-$precision));
        }
        return '0.' . str_repeat('0', $precision - 1) . '1';
    }

    protected function operator(string $operator, $value, int $scale = null): DecimalInterface
    {
        $function = 'bc' . $operator;

        if (!function_exists($function)) {
            throw new \InvalidArgumentException('Invalid operator: ' . $operator);
        }

        $newScale = $scale ?? $this->scale;

        $newValue = call_user_func(
            $function,
            $this->value,
            static::create($value, $newScale)->getValue(),
            $newScale
        );

        return $this->mutate()->setScale($newScale)->setValue($newValue);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value = null): DecimalInterface
    {
        if (!preg_match(static::RE_NUMBER, $value)) {
            throw new \InvalidArgumentException("Invalid number: $value.");
        }

        $this->value = bcadd($value, 0, $this->scale);
        return $this;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function setScale(int $scale = null): DecimalInterface
    {
        if ($scale < 0) {
            throw new \InvalidArgumentException('Scale must be positive.');
        }

        $this->scale = $scale ?: static::$defaultScale ?: 0;
        $this->value = bcadd($this->value, 0, $this->scale);
        return $this;
    }

    public function add($value, $scale = null): DecimalInterface
    {
        return $this->operator('add', $value, $scale);
    }

    public function subtract($value, $scale = null): DecimalInterface
    {
        return $this->operator('sub', $value, $scale);
    }

    public function multiply($value, $scale = null): DecimalInterface
    {
        return $this->operator('mul', $value, $scale);
    }

    public function divide($value, $scale = null): DecimalInterface
    {
        return $this->operator('div', $value, $scale);
    }

    public function modulus($value): DecimalInterface
    {
        $newValue = bcmod($this->value, $value);
        return $this->mutate()->setValue($newValue);
    }

    public function power($value, $scale = null): DecimalInterface
    {
        $exp = (int)static::create($value)->getValue();
        $newScale = $scale ?? $this->scale;

        if ($exp >= 0) {
            $newValue = bcpow($this->value, (string)$exp, $newScale);
        } else {
            $newValue = bcdiv('1', bcpow($this->value, (string)(-$exp), $newScale), $newScale);
        }

        return $this->mutate()->setScale($newScale)->setValue($newValue);
    }

    public function squareRoot($scale = null): DecimalInterface
    {
        $newScale = $scale ?? $this->scale;
        $newValue = bcsqrt($this->value, $newScale);

        return $this->mutate()->setScale($newScale)->setValue($newValue);
    }

    public function round(int $precision = 0, int $mode = self::ROUND_HALF_UP): DecimalInterface
    {
        switch ($mode) {
            case static::ROUND_UP:
                return $this->roundUp($precision);
            case static::ROUND_DOWN:
                return $this->roundDown($precision);
            case static::ROUND_HALF_UP:
                return $this->roundHalfUp($precision);
            case static::ROUND_HALF_DOWN:
                return $this->roundHalfDown($precision);
            case static::ROUND_HALF_EVEN:
                return $this->roundHalfEven($precision);
            case static::ROUND_HALF_ODD:
                return $this->roundHalfOdd($precision);
        }
        throw new \InvalidArgumentException('Invalid rounding mode.');
    }

    public function truncate(int $precision = 0): DecimalInterface
    {
        if ($precision >= 0) {
            return $this->mutate()->setScale($precision);
        } else {
            $base = bcpow(10, -$precision);
            return $this->divide($base, 0)->multiply($base);
        }
    }

    public function roundUp(int $precision = 0): DecimalInterface
    {
        $original = $this->toDecimal();
        $truncated = $this->truncate($precision);

        if ($precision > $original->getScale()) {
            return $truncated;
        }

        if ($original->isGreaterThan($truncated)) {
            $increment = self::incrementAtPrecision($precision);
            $truncated = $truncated->add($increment);
        }

        return $truncated;
    }

    public function roundDown(int $precision = 0): DecimalInterface
    {
        return $this->truncate($precision);
    }

    private function roundHalf(int $precision = 0, bool $roundOnTie = false, callable $tieBreaker = null): DecimalInterface
    {
        $original = $this->toDecimal();
        $isNegative = $this->isNegative();
        $truncated = $this->truncate($precision);
        $originalScale = $original->getScale();

        if ($precision > $originalScale) {
            return $truncated;
        }

        $increment = self::incrementAtPrecision($precision);
        $compare = bcmul($increment, '0.5', $originalScale + 1);
        $absDiff = $original->subtract($truncated)->absolutize();
        $cmp = $absDiff->compareTo($compare, $originalScale + 1);

        if ($cmp > 0 || ($cmp === 0 && ($roundOnTie || ($tieBreaker !== null && $tieBreaker($truncated, $increment, $precision))))) {
            return $isNegative ? $truncated->subtract($increment) : $truncated->add($increment);
        }

        return $truncated;
    }

    private function roundHalfEvenOdd(int $precision = 0, bool $isEven = false): DecimalInterface
    {
        return $this->roundHalf($precision, false, function (DecimalInterface $truncated, string $increment, int $precision) use ($isEven): bool {
            if ($precision > 0) {
                $digitValue = $truncated->toDecimal()->multiply(bcpow('10', (string)$precision), 0);
            } else {
                $digitValue = $truncated->toDecimal()->divide($increment, 0);
            }

            return $digitValue->modulus(2)->isNotZero() == $isEven;
        });
    }

    public function roundHalfUp(int $precision = 0): DecimalInterface
    {
        return $this->roundHalf($precision, true);
    }

    public function roundHalfDown(int $precision = 0): DecimalInterface
    {
        return $this->roundHalf($precision);
    }

    public function roundHalfEven(int $precision = 0): DecimalInterface
    {
        return $this->roundHalfEvenOdd($precision, true);
    }

    public function roundHalfOdd(int $precision = 0): DecimalInterface
    {
        return $this->roundHalfEvenOdd($precision);
    }

    public function floor(int $precision = 0): DecimalInterface
    {
        $original = $this->toDecimal();
        $truncated = $this->truncate($precision);

        if ($original->isLessThan($truncated)) {
            $increment = self::incrementAtPrecision($precision);
            $truncated = $truncated->subtract($increment);
        }

        return $truncated;
    }

    public function ceil(int $precision = 0): DecimalInterface
    {
        return $this->roundUp($precision);
    }

    public function inverse(): DecimalInterface
    {
        return $this->multiply(-1);
    }

    public function negate(): DecimalInterface
    {
        return $this->inverse();
    }

    public function absolutize(): DecimalInterface
    {
        return $this->multiply($this->isNegative() ? -1 : 1);
    }

    public function toFloat(): float
    {
        return (float)$this->getValue();
    }

    public function toInt(): int
    {
        return (int)$this->getValue();
    }

    public function toString(bool $trailingZeros = false): string
    {
        $value = $this->value;

        if (!$trailingZeros && strstr($value, '.') !== false) {
            $value = rtrim(rtrim($value, '0'), '.');
        }

        return $value;
    }

    public function toDecimal(): DecimalInterface
    {
        return new static($this->value, $this->scale);
    }

    public function toMutable(): DecimalInterface
    {
        return new Decimal($this->value, $this->scale);
    }

    public function toImmutable(): DecimalInterface
    {
        return new DecimalImmutable($this->value, $this->scale);
    }

    public function toDigits(int $digits): DecimalInterface
    {
        if ($digits < 1) {
            throw new \InvalidArgumentException('Digits must be at least 1.');
        }

        if ($this->isZero()) {
            return $this->mutate();
        }

        $absValue = ltrim($this->value, '-');
        $parts = explode('.', $absValue);
        $intPart = $parts[0];

        if ($intPart !== '0') {
            $precision = $digits - strlen($intPart);
        } else {
            $fracPart = isset($parts[1]) ? $parts[1] : '';
            $leadingZeros = strlen($fracPart) - strlen(ltrim($fracPart, '0'));
            $precision = $leadingZeros + $digits;
        }

        // For non-positive precision, delegate to round (works correctly for these cases)
        if ($precision <= 0) {
            return $this->round($precision, self::ROUND_HALF_UP);
        }

        // More digits requested than available, pad with zeros
        if ($precision > $this->scale) {
            return $this->mutate()->setScale($precision);
        }

        // Round half-up to $precision decimal places
        $half = '0.' . str_repeat('0', $precision) . '5';
        if ($this->isNegative()) {
            $rounded = bcsub($this->value, $half, $precision);
        } else {
            $rounded = bcadd($this->value, $half, $precision);
        }

        return $this->mutate()->setScale($precision)->setValue($rounded);
    }

    public function toScientific(int $precision = 5, string $exponent = 'E'): string
    {
        $value = ltrim($this->value, '-');
        $isNegative = $this->isNegative();

        $dotPos = strpos($value, '.');
        if ($dotPos === false) {
            $dotPos = strlen($value);
        }

        $digitPos = false;

        for ($i = 0; $i < strlen($value); $i++) {
            if ($value[$i] >= '1' && $value[$i] <= '9') {
                $digitPos = $i;
                break;
            }
        }

        if ($digitPos === false) {
            return ($isNegative ? '-' : '') . '0' . $exponent . '0';
        }

        // Calculate power: digits before dot contribute positively, after dot negatively
        if ($digitPos < $dotPos) {
            $power = $dotPos - $digitPos - 1;
        } else {
            // digitPos is after the dot; adjust for the dot character
            $power = $dotPos - $digitPos;
        }

        $base = bcpow(10, abs($power));

        if ($power > 0) {
            $number = bcdiv($this->value, $base, $this->scale);
        } elseif ($power < 0) {
            $number = bcmul($this->value, $base, $this->scale);
        } else {
            $number = $this->value;
        }

        // Strip trailing zeros after decimal point
        if (strpos($number, '.') !== false) {
            $number = rtrim(rtrim($number, '0'), '.');
        }

        return $number . $exponent . $power;
    }

    public function toFormat(int $precision = 0, string $decPoint = '.', string $thousandsSep = ',', bool $trailingZeros = false, int $mode = PHP_ROUND_HALF_UP): string
    {
        $rounded = $this->round($precision, $mode)->toString($trailingZeros);
        $parts = explode('.', $rounded);
        $reversed = strrev($parts[0]);
        $reversedParts = explode('-', $reversed);
        $reversedParts[0] = rtrim(preg_replace('/\d{3}/', '\\0' . $thousandsSep, $reversedParts[0]), $thousandsSep);
        $parts[0] = strrev(implode('-', $reversedParts));
        return implode($decPoint, $parts);
    }

    public function toFixed(int $precision = 0): string
    {
        return $this->setScale($precision)->getValue();
    }

    public function compareTo($arg, int $scale = null): int
    {
        return bccomp(
            $this->value,
            static::create($arg, $scale ?? $this->scale)->getValue(),
            $scale ?? $this->scale
        );
    }

    public function isEqual($arg, int $scale = null): bool
    {
        return $this->compareTo($arg, $scale) == 0;
    }

    public function isNotEqual($arg, int $scale = null): bool
    {
        return !$this->isEqual($arg, $scale);
    }

    public function isGreaterThan($arg, int $scale = null): bool
    {
        return $this->compareTo($arg, $scale) > 0;
    }

    public function isGreaterThanOrEquals($arg, int $scale = null): bool
    {
        return $this->compareTo($arg, $scale) >= 0;
    }

    public function isLessThan($arg, int $scale = null): bool
    {
        return $this->compareTo($arg, $scale) < 0;
    }

    public function isLessThanOrEquals($arg, int $scale = null): bool
    {
        return $this->compareTo($arg, $scale) <= 0;
    }

    public function isInteger(): bool
    {
        return $this->isEqual($this->toDecimal()->setScale(0));
    }

    public function isDecimal(): bool
    {
        return !$this->isInteger();
    }

    public function isNegative(): bool
    {
        return $this->isLessThan(0);
    }

    public function isPositive(): bool
    {
        return $this->isGreaterThanOrEquals(0);
    }

    public function isZero(): bool
    {
        return $this->isEqual(0);
    }

    public function isNotZero(): bool
    {
        return $this->isNotEqual(0);
    }

    public function isEven(): bool
    {
        return $this->toDecimal()->modulus(2)->isZero();
    }

    public function isOdd(): bool
    {
        return !$this->isEven();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
