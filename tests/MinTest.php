<?php

use AndKom\Decimal;

class MinTest extends \PHPUnit\Framework\TestCase
{
    public function testMin()
    {
        $this->assertSame(Decimal::min(-1)->getValue(), '-1');
        $this->assertSame(Decimal::min(0)->getValue(), '0');
        $this->assertSame(Decimal::min(1)->getValue(), '1');
        $this->assertSame(Decimal::min(0, 1)->getValue(), '0');
        $this->assertSame(Decimal::min(1, 0)->getValue(), '0');
        $this->assertSame(Decimal::min(1, 1)->getValue(), '1');
        $this->assertSame(Decimal::min(0, 0)->getValue(), '0');
        $this->assertSame(Decimal::min(-1, 0)->getValue(), '-1');
        $this->assertSame(Decimal::min(-1, -2)->getValue(), '-2');
    }
}