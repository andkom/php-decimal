<?php

use AndKom\Decimal;
use AndKom\DecimalImmutable;

class ConversionTest extends \PHPUnit\Framework\TestCase
{
    public function testToFloat()
    {
        $this->assertSame((new Decimal('1'))->toFloat(), 1.0);
        $this->assertSame((new Decimal('1.5', 1))->toFloat(), 1.5);
        $this->assertSame((new Decimal('0'))->toFloat(), 0.0);
        $this->assertSame((new Decimal('-3.14', 2))->toFloat(), -3.14);
    }

    public function testToInt()
    {
        $this->assertSame((new Decimal('1'))->toInt(), 1);
        $this->assertSame((new Decimal('1.9', 1))->toInt(), 1);
        $this->assertSame((new Decimal('0'))->toInt(), 0);
        $this->assertSame((new Decimal('-3'))->toInt(), -3);
    }

    public function testToStringMethod()
    {
        // without trailing zeros
        $this->assertSame((new Decimal('1.50', 2))->toString(), '1.5');
        $this->assertSame((new Decimal('1.00', 2))->toString(), '1');
        $this->assertSame((new Decimal('0'))->toString(), '0');
        $this->assertSame((new Decimal('123'))->toString(), '123');

        // with trailing zeros
        $this->assertSame((new Decimal('1.50', 2))->toString(true), '1.50');
        $this->assertSame((new Decimal('1.00', 2))->toString(true), '1.00');
        $this->assertSame((new Decimal('0', 2))->toString(true), '0.00');
    }

    public function testToDecimal()
    {
        $d = new Decimal('1.23', 2);
        $copy = $d->toDecimal();

        $this->assertSame($copy->getValue(), '1.23');
        $this->assertSame($copy->getScale(), 2);
        $this->assertNotSame($d, $copy);
    }

    public function testToMutable()
    {
        $immutable = new DecimalImmutable('1.23', 2);
        $mutable = $immutable->toMutable();

        $this->assertInstanceOf(Decimal::class, $mutable);
        $this->assertSame($mutable->getValue(), '1.23');
        $this->assertSame($mutable->getScale(), 2);
    }

    public function testToImmutable()
    {
        $mutable = new Decimal('1.23', 2);
        $immutable = $mutable->toImmutable();

        $this->assertInstanceOf(DecimalImmutable::class, $immutable);
        $this->assertSame($immutable->getValue(), '1.23');
        $this->assertSame($immutable->getScale(), 2);
    }

    public function testToFixed()
    {
        $this->assertSame((new Decimal('1.2345', 4))->toFixed(2), '1.23');
        $this->assertSame((new Decimal('1.2345', 4))->toFixed(0), '1');
        $this->assertSame((new Decimal('1', 0))->toFixed(3), '1.000');
        $this->assertSame((new Decimal('-1.5', 1))->toFixed(0), '-1');
        $this->assertSame((new Decimal('0'))->toFixed(2), '0.00');
    }

    public function testToFixedDoesNotMutate()
    {
        $d = new Decimal('1.2345', 4);
        $d->toFixed(2);
        $this->assertSame($d->getValue(), '1.2345');
        $this->assertSame($d->getScale(), 4);
    }

    public function testToFormat()
    {
        $this->assertSame((new Decimal('1234567'))->toFormat(), '1,234,567');
        $this->assertSame((new Decimal('1234.56', 2))->toFormat(2), '1,234.56');
        $this->assertSame((new Decimal('1000000'))->toFormat(0, '.', ','), '1,000,000');
        $this->assertSame((new Decimal('1234567'))->toFormat(0, ',', '.'), '1.234.567');
        $this->assertSame((new Decimal('0'))->toFormat(), '0');
        $this->assertSame((new Decimal('-1234567'))->toFormat(), '-1,234,567');

        // with trailing zeros
        $this->assertSame((new Decimal('1234', 0))->toFormat(2, '.', ',', true), '1,234.00');
    }
}
