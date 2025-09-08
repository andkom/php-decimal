<?php

use AndKom\Decimal;

class MaxTest extends \PHPUnit\Framework\TestCase
{
    public function testMax()
    {
        $this->assertSame(Decimal::max(-1)->getValue(), '-1');
        $this->assertSame(Decimal::max(0)->getValue(), '0');
        $this->assertSame(Decimal::max(1)->getValue(), '1');
        $this->assertSame(Decimal::max(0, 1)->getValue(), '1');
        $this->assertSame(Decimal::max(1, 0)->getValue(), '1');
        $this->assertSame(Decimal::max(0, 0)->getValue(), '0');
        $this->assertSame(Decimal::max(1, 1)->getValue(), '1');
        $this->assertSame(Decimal::max(-1, 0)->getValue(), '0');
        $this->assertSame(Decimal::max(-1, -2)->getValue(), '-1');
    }
}