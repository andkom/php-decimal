<?php

use AndKom\Decimal;

class ToDigitsTest extends \PHPUnit\Framework\TestCase
{
    public function testToDigitsInteger()
    {
        $this->assertSame((new Decimal('123.456', 3))->toDigits(5)->getValue(), '123.46');
        $this->assertSame((new Decimal('123.456', 3))->toDigits(4)->getValue(), '123.5');
        $this->assertSame((new Decimal('123.456', 3))->toDigits(3)->getValue(), '123');
        $this->assertSame((new Decimal('123.456', 3))->toDigits(2)->getValue(), '120');
        $this->assertSame((new Decimal('123.456', 3))->toDigits(1)->getValue(), '100');
    }

    public function testToDigitsFractional()
    {
        $this->assertSame((new Decimal('0.00123', 5))->toDigits(3)->getValue(), '0.00123');
        $this->assertSame((new Decimal('0.00123', 5))->toDigits(2)->getValue(), '0.0012');
        $this->assertSame((new Decimal('0.00123', 5))->toDigits(1)->getValue(), '0.001');
    }

    public function testToDigitsNegative()
    {
        $this->assertSame((new Decimal('-123.456', 3))->toDigits(4)->getValue(), '-123.5');
        $this->assertSame((new Decimal('-123.456', 3))->toDigits(3)->getValue(), '-123');
        $this->assertSame((new Decimal('-0.00123', 5))->toDigits(2)->getValue(), '-0.0012');
    }

    public function testToDigitsZero()
    {
        $this->assertSame((new Decimal('0'))->toDigits(3)->getValue(), '0');
    }

    public function testToDigitsNoRounding()
    {
        $this->assertSame((new Decimal('123'))->toDigits(3)->getValue(), '123');
        $this->assertSame((new Decimal('1.5', 1))->toDigits(2)->getValue(), '1.5');
        $this->assertSame((new Decimal('123.456', 3))->toDigits(6)->getValue(), '123.456');
    }

    public function testToDigitsExtendScale()
    {
        $this->assertSame((new Decimal('123'))->toDigits(5)->getValue(), '123.00');
        $this->assertSame((new Decimal('0.001', 3))->toDigits(5)->getValue(), '0.0010000');
    }

    public function testToDigitsRoundsHalfUp()
    {
        $this->assertSame((new Decimal('1.45', 2))->toDigits(2)->getValue(), '1.5');
        $this->assertSame((new Decimal('1.44', 2))->toDigits(2)->getValue(), '1.4');
        $this->assertSame((new Decimal('125'))->toDigits(2)->getValue(), '130');
        $this->assertSame((new Decimal('163'))->toDigits(1)->getValue(), '200');
    }

    public function testToDigitsInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Decimal('123'))->toDigits(0);
    }
}
