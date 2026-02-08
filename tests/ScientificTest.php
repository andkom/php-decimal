<?php

use AndKom\Decimal;

class ScientificTest extends \PHPUnit\Framework\TestCase
{
    public function testToScientific()
    {
        $this->assertSame((new Decimal('0'))->toScientific(), '0E0');

        $this->assertSame((new Decimal('1'))->toScientific(), '1E0');
        $this->assertSame((new Decimal('10'))->toScientific(), '1E1');
        $this->assertSame((new Decimal('10000'))->toScientific(), '1E4');

        $this->assertSame((new Decimal('-1'))->toScientific(), '-1E0');
        $this->assertSame((new Decimal('-10'))->toScientific(), '-1E1');
        $this->assertSame((new Decimal('-10000'))->toScientific(), '-1E4');

        $this->assertSame((new Decimal('0.1', 1))->toScientific(), '1E-1');
        $this->assertSame((new Decimal('0.0001', 4))->toScientific(), '1E-4');
    }

    public function testToScientificWithPrecision()
    {
        // precision controls decimal places in mantissa
        $this->assertSame((new Decimal('12345'))->toScientific(3), '1.234E4');
        $this->assertSame((new Decimal('12345'))->toScientific(0), '1E4');
        $this->assertSame((new Decimal('12345'))->toScientific(1), '1.2E4');

        $this->assertSame((new Decimal('0.00456', 5))->toScientific(2), '4.56E-3');
        $this->assertSame((new Decimal('0.00456', 5))->toScientific(0), '4E-3');

        $this->assertSame((new Decimal('9.876', 3))->toScientific(2), '9.87E0');
        $this->assertSame((new Decimal('9.876', 3))->toScientific(0), '9E0');

        // negative values
        $this->assertSame((new Decimal('-12345'))->toScientific(2), '-1.23E4');
        $this->assertSame((new Decimal('-0.00456', 5))->toScientific(2), '-4.56E-3');

        // trailing zeros stripped
        $this->assertSame((new Decimal('10000'))->toScientific(5), '1E4');
        $this->assertSame((new Decimal('1.5', 1))->toScientific(5), '1.5E0');

        // zero
        $this->assertSame((new Decimal('0'))->toScientific(3), '0E0');
    }

    public function testToScientificCustomExponent()
    {
        $this->assertSame((new Decimal('12345'))->toScientific(2, 'e'), '1.23e4');
        $this->assertSame((new Decimal('0.001', 3))->toScientific(1, 'x10^'), '1x10^-3');
    }
}