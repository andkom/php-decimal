<?php

use AndKom\Decimal;

class NegateTest extends \PHPUnit\Framework\TestCase
{
    public function testNegatePositive()
    {
        $this->assertSame((new Decimal('1'))->negate()->getValue(), '-1');
        $this->assertSame((new Decimal('123'))->negate()->getValue(), '-123');
        $this->assertSame((new Decimal('1.50', 2))->negate()->getValue(), '-1.50');
    }

    public function testNegateNegative()
    {
        $this->assertSame((new Decimal('-1'))->negate()->getValue(), '1');
        $this->assertSame((new Decimal('-123'))->negate()->getValue(), '123');
        $this->assertSame((new Decimal('-1.50', 2))->negate()->getValue(), '1.50');
    }

    public function testNegateZero()
    {
        $this->assertSame((new Decimal('0'))->negate()->getValue(), '0');
        $this->assertSame((new Decimal('0.00', 2))->negate()->getValue(), '0.00');
    }

    public function testDoubleNegate()
    {
        $this->assertSame((new Decimal('5'))->negate()->negate()->getValue(), '5');
        $this->assertSame((new Decimal('-5'))->negate()->negate()->getValue(), '-5');
    }
}
