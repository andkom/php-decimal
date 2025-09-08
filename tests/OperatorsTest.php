<?php

use AndKom\Decimal;

class OperatorsTest extends \PHPUnit\Framework\TestCase
{
    public function testPower()
    {
        $this->assertSame((new Decimal(0))->power(0)->getValue(), '1');
        $this->assertSame((new Decimal(0))->power(1)->getValue(), '0');
        $this->assertSame((new Decimal(1))->power(1)->getValue(), '1');
        $this->assertSame((new Decimal(2))->power(0)->getValue(), '1');
        $this->assertSame((new Decimal(2))->power(1)->getValue(), '2');
        $this->assertSame((new Decimal(2))->power(2)->getValue(), '4');
        $this->assertSame((new Decimal(2))->power(3)->getValue(), '8');
        $this->assertSame((new Decimal(2))->power(-3, 3)->getValue(), '0.125');
        $this->assertSame((new Decimal(2))->power(-2, 2)->getValue(), '0.25');
        $this->assertSame((new Decimal(2))->power(-1, 1)->getValue(), '0.5');
    }
}