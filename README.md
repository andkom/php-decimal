# php-decimal

Arbitrary-precision decimal arithmetic library for PHP, built on top of the `bcmath` extension.

Provides both mutable (`Decimal`) and immutable (`DecimalImmutable`) implementations with a full set of arithmetic operations, rounding modes, comparisons, and formatting options.

## Requirements

- PHP 7.4+
- `bcmath` extension

## Installation

```bash
composer require andkom/php-decimal
```

## Quick Start

```php
use AndKom\Decimal;
use AndKom\DecimalImmutable;

// Create from various types
$a = new Decimal('1.5', 2);
$b = Decimal::create(2);
$c = Decimal::createFromFloat(3.14, 2);

// Arithmetic (mutable - modifies in place)
$result = (new Decimal('10', 2))->add('3.5')->multiply('2');
echo $result->getValue(); // '27.00'

// Immutable - always returns a new instance
$price = new DecimalImmutable('19.99', 2);
$discounted = $price->multiply('0.9');
echo $price->getValue();      // '19.99' (unchanged)
echo $discounted->getValue();  // '17.99'

// Rounding
echo (new Decimal('2.5', 1))->roundHalfUp()->getValue();   // '3'
echo (new Decimal('2.5', 1))->roundHalfEven()->getValue();  // '2'

// Formatting
echo (new Decimal('1234567.89', 2))->toFormat(2); // '1,234,567.89'
echo (new Decimal('1.5', 1))->toScientific(2);    // '1.5E0'
echo (new Decimal('1.234', 3))->toFixed(2);        // '1.23'

// Comparisons
$a = new Decimal('1.5', 1);
$a->isGreaterThan('1');    // true
$a->isEqual('1.5');        // true
$a->isPositive();          // true
```

## Creating Decimals

| Method | Description |
|--------|-------------|
| `new Decimal($value, $scale)` | Constructor, value is a string, scale is optional |
| `Decimal::create($value, $scale)` | Factory, auto-detects type (string, int, float, Decimal) |
| `Decimal::createFromString($value, $scale)` | From string, supports scientific notation (e.g. `'1.5e-3'`) |
| `Decimal::createFromFloat($value, $scale)` | From float, auto-detects scale if not provided |
| `Decimal::createFromInteger($value, $scale)` | From integer |
| `Decimal::createFromDecimal($decimal, $scale)` | Clone from another Decimal |

## Default Scale

```php
Decimal::setDefaultScale(4);
echo (new Decimal('1'))->getValue(); // '1.0000'
echo Decimal::getDefaultScale();     // 4
```

## Arithmetic

All arithmetic methods accept an optional `$scale` parameter to override the result scale.

| Method | Description |
|--------|-------------|
| `add($value, $scale)` | Addition |
| `subtract($value, $scale)` | Subtraction |
| `multiply($value, $scale)` | Multiplication |
| `divide($value, $scale)` | Division |
| `modulus($value)` | Modulo (remainder) |
| `power($value, $scale)` | Exponentiation (supports negative exponents) |
| `squareRoot($scale)` | Square root |

```php
echo (new Decimal('10', 2))->divide('3', 4)->getValue(); // '3.3333'
echo (new Decimal('2'))->power(-3, 3)->getValue();        // '0.125'
echo (new Decimal('2'))->squareRoot(4)->getValue();        // '1.4142'
```

## Rounding

All rounding methods accept an optional `$precision` parameter. Positive precision rounds decimal places, negative precision rounds to powers of 10.

| Method | Description |
|--------|-------------|
| `round($precision, $mode)` | Round with specified mode (default: ROUND_HALF_UP) |
| `roundUp($precision)` | Round away from zero |
| `roundDown($precision)` | Round toward zero (truncation) |
| `roundHalfUp($precision)` | Round half away from zero |
| `roundHalfDown($precision)` | Round half toward zero |
| `roundHalfEven($precision)` | Round half to even (banker's rounding) |
| `roundHalfOdd($precision)` | Round half to odd |
| `floor($precision)` | Round toward negative infinity |
| `ceil($precision)` | Round toward positive infinity |
| `truncate($precision)` | Truncate toward zero |

### Rounding Mode Constants

| Constant | Value |
|----------|-------|
| `ROUND_UP` | 1 |
| `ROUND_DOWN` | 2 |
| `ROUND_HALF_UP` | 3 |
| `ROUND_HALF_DOWN` | 4 |
| `ROUND_HALF_EVEN` | 5 |
| `ROUND_HALF_ODD` | 6 |

```php
echo (new Decimal('2.5', 1))->roundHalfUp()->getValue();   // '3'
echo (new Decimal('2.5', 1))->roundHalfDown()->getValue();  // '2'
echo (new Decimal('2.5', 1))->roundHalfEven()->getValue();  // '2'
echo (new Decimal('3.5', 1))->roundHalfEven()->getValue();  // '4'
echo (new Decimal('-2.1', 1))->roundUp()->getValue();       // '-3'
echo (new Decimal('-2.1', 1))->ceil()->getValue();          // '-2'
echo (new Decimal('-2.1', 1))->floor()->getValue();         // '-3'
echo (new Decimal('123'))->round(-1)->getValue();           // '120'
```

## Sign Operations

| Method | Description |
|--------|-------------|
| `inverse()` | Flip sign (positive becomes negative and vice versa) |
| `negate()` | Always return negative value |
| `absolutize()` | Always return positive value (absolute value) |

```php
echo (new Decimal('-5'))->inverse()->getValue();    // '5'
echo (new Decimal('5'))->inverse()->getValue();     // '-5'
echo (new Decimal('5'))->negate()->getValue();      // '-5'
echo (new Decimal('-5'))->negate()->getValue();     // '-5'
echo (new Decimal('-5'))->absolutize()->getValue(); // '5'
```

## Conversion & Formatting

| Method | Description |
|--------|-------------|
| `toFloat()` | Convert to PHP float |
| `toInt()` | Convert to PHP int (truncates decimal part) |
| `toString($trailingZeros)` | String without trailing zeros (default), or with |
| `toFixed($precision)` | String with exact decimal places (does not mutate) |
| `toFormat($precision, $decPoint, $thousandsSep, $trailingZeros, $mode)` | Formatted number with thousands separator |
| `toScientific($precision, $exponent)` | Scientific notation (e.g. `'1.23E4'`) |
| `toDigits($digits)` | Round to N significant digits |
| `toDecimal()` | Clone as new instance of same type |
| `toMutable()` | Convert to mutable `Decimal` |
| `toImmutable()` | Convert to immutable `DecimalImmutable` |

```php
$d = new Decimal('1234.5678', 4);

echo $d->toFixed(2);               // '1234.56'
echo $d->toFormat(2);              // '1,234.57'
echo $d->toScientific(3);          // '1.234E3'
echo $d->toDigits(3)->getValue();  // '1230'
echo $d->toString();               // '1234.5678'
echo $d->toFloat();                // 1234.5678
echo $d->toInt();                  // 1234
```

## Comparisons

All comparison methods accept an optional `$scale` parameter.

| Method | Description |
|--------|-------------|
| `compareTo($arg, $scale)` | Returns -1, 0, or 1 |
| `isEqual($arg, $scale)` | Equal to |
| `isNotEqual($arg, $scale)` | Not equal to |
| `isGreaterThan($arg, $scale)` | Greater than |
| `isGreaterThanOrEquals($arg, $scale)` | Greater than or equal |
| `isLessThan($arg, $scale)` | Less than |
| `isLessThanOrEquals($arg, $scale)` | Less than or equal |

## Predicates

| Method | Description |
|--------|-------------|
| `isInteger()` | True if no fractional part |
| `isDecimal()` | True if has fractional part |
| `isPositive()` | True if greater than zero |
| `isNegative()` | True if less than zero |
| `isZero()` | True if equal to zero |
| `isNotZero()` | True if not equal to zero |
| `isEven()` | True if integer part is even |
| `isOdd()` | True if integer part is odd |

## Static Helpers

| Method | Description |
|--------|-------------|
| `Decimal::max(...$args)` | Returns the maximum value |
| `Decimal::min(...$args)` | Returns the minimum value |

```php
echo Decimal::max('1', '5', '3')->getValue(); // '5'
echo Decimal::min('1', '5', '3')->getValue(); // '1'
```

## Mutable vs Immutable

`Decimal` is mutable -- operations modify the instance in place and return `$this`:

```php
$d = new Decimal('10');
$d->add('5');
echo $d->getValue(); // '15' (modified)
```

`DecimalImmutable` always returns a new instance, leaving the original unchanged:

```php
$d = new DecimalImmutable('10');
$result = $d->add('5');
echo $d->getValue();      // '10' (unchanged)
echo $result->getValue();  // '15' (new instance)
```

Convert between them with `toMutable()` and `toImmutable()`.

## Running Tests

```bash
composer install
vendor/bin/phpunit
```
