<?php

use AndKom\Decimal;

class ComparisonTest extends \PHPUnit\Framework\TestCase
{
    public function testCompareTo()
    {
        $this->assertSame((new Decimal('1'))->compareTo('1'), 0);
        $this->assertSame((new Decimal('1'))->compareTo('2'), -1);
        $this->assertSame((new Decimal('2'))->compareTo('1'), 1);
        $this->assertSame((new Decimal('0'))->compareTo('0'), 0);
        $this->assertSame((new Decimal('-1'))->compareTo('1'), -1);
        $this->assertSame((new Decimal('1.00', 2))->compareTo('1'), 0);

        // with explicit scale
        $this->assertSame((new Decimal('1.001', 3))->compareTo('1', 2), 0);
        $this->assertSame((new Decimal('1.001', 3))->compareTo('1', 3), 1);
    }

    public function testIsEqual()
    {
        $this->assertTrue((new Decimal('1'))->isEqual('1'));
        $this->assertTrue((new Decimal('1.00', 2))->isEqual('1'));
        $this->assertTrue((new Decimal('0'))->isEqual('0'));
        $this->assertFalse((new Decimal('1'))->isEqual('2'));
        $this->assertFalse((new Decimal('-1'))->isEqual('1'));
    }

    public function testIsNotEqual()
    {
        $this->assertTrue((new Decimal('1'))->isNotEqual('2'));
        $this->assertTrue((new Decimal('-1'))->isNotEqual('1'));
        $this->assertFalse((new Decimal('1'))->isNotEqual('1'));
        $this->assertFalse((new Decimal('1.00', 2))->isNotEqual('1'));
    }

    public function testIsGreaterThan()
    {
        $this->assertTrue((new Decimal('2'))->isGreaterThan('1'));
        $this->assertTrue((new Decimal('0'))->isGreaterThan('-1'));
        $this->assertFalse((new Decimal('1'))->isGreaterThan('1'));
        $this->assertFalse((new Decimal('1'))->isGreaterThan('2'));
    }

    public function testIsGreaterThanOrEquals()
    {
        $this->assertTrue((new Decimal('2'))->isGreaterThanOrEquals('1'));
        $this->assertTrue((new Decimal('1'))->isGreaterThanOrEquals('1'));
        $this->assertTrue((new Decimal('1.00', 2))->isGreaterThanOrEquals('1'));
        $this->assertFalse((new Decimal('1'))->isGreaterThanOrEquals('2'));
        $this->assertFalse((new Decimal('-1'))->isGreaterThanOrEquals('0'));
    }

    public function testIsLessThan()
    {
        $this->assertTrue((new Decimal('1'))->isLessThan('2'));
        $this->assertTrue((new Decimal('-1'))->isLessThan('0'));
        $this->assertFalse((new Decimal('1'))->isLessThan('1'));
        $this->assertFalse((new Decimal('2'))->isLessThan('1'));
    }

    public function testIsLessThanOrEquals()
    {
        $this->assertTrue((new Decimal('1'))->isLessThanOrEquals('2'));
        $this->assertTrue((new Decimal('1'))->isLessThanOrEquals('1'));
        $this->assertTrue((new Decimal('1.00', 2))->isLessThanOrEquals('1'));
        $this->assertFalse((new Decimal('2'))->isLessThanOrEquals('1'));
        $this->assertFalse((new Decimal('0'))->isLessThanOrEquals('-1'));
    }
}
