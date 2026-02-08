<?php

use AndKom\Decimal;

class PredicateTest extends \PHPUnit\Framework\TestCase
{
    public function testIsPositive()
    {
        $this->assertTrue((new Decimal('1'))->isPositive());
        $this->assertTrue((new Decimal('0.01', 2))->isPositive());
        $this->assertFalse((new Decimal('0'))->isPositive());
        $this->assertFalse((new Decimal('-1'))->isPositive());
    }

    public function testIsNegative()
    {
        $this->assertTrue((new Decimal('-1'))->isNegative());
        $this->assertTrue((new Decimal('-0.01', 2))->isNegative());
        $this->assertFalse((new Decimal('0'))->isNegative());
        $this->assertFalse((new Decimal('1'))->isNegative());
    }

    public function testIsZero()
    {
        $this->assertTrue((new Decimal('0'))->isZero());
        $this->assertTrue((new Decimal('0.00', 2))->isZero());
        $this->assertFalse((new Decimal('1'))->isZero());
        $this->assertFalse((new Decimal('-1'))->isZero());
    }

    public function testIsNotZero()
    {
        $this->assertTrue((new Decimal('1'))->isNotZero());
        $this->assertTrue((new Decimal('-1'))->isNotZero());
        $this->assertFalse((new Decimal('0'))->isNotZero());
        $this->assertFalse((new Decimal('0.00', 2))->isNotZero());
    }

    public function testIsInteger()
    {
        $this->assertTrue((new Decimal('1'))->isInteger());
        $this->assertTrue((new Decimal('0'))->isInteger());
        $this->assertTrue((new Decimal('-5'))->isInteger());
        $this->assertTrue((new Decimal('1.00', 2))->isInteger());
        $this->assertFalse((new Decimal('1.5', 1))->isInteger());
        $this->assertFalse((new Decimal('0.01', 2))->isInteger());
    }

    public function testIsDecimal()
    {
        $this->assertTrue((new Decimal('1.5', 1))->isDecimal());
        $this->assertTrue((new Decimal('0.01', 2))->isDecimal());
        $this->assertFalse((new Decimal('1'))->isDecimal());
        $this->assertFalse((new Decimal('0'))->isDecimal());
        $this->assertFalse((new Decimal('1.00', 2))->isDecimal());
    }

    public function testIsEven()
    {
        $this->assertTrue((new Decimal('0'))->isEven());
        $this->assertTrue((new Decimal('2'))->isEven());
        $this->assertTrue((new Decimal('-4'))->isEven());
        $this->assertFalse((new Decimal('1'))->isEven());
        $this->assertFalse((new Decimal('3'))->isEven());
        $this->assertFalse((new Decimal('-5'))->isEven());
    }

    public function testIsOdd()
    {
        $this->assertTrue((new Decimal('1'))->isOdd());
        $this->assertTrue((new Decimal('3'))->isOdd());
        $this->assertTrue((new Decimal('-5'))->isOdd());
        $this->assertFalse((new Decimal('0'))->isOdd());
        $this->assertFalse((new Decimal('2'))->isOdd());
        $this->assertFalse((new Decimal('-4'))->isOdd());
    }
}
