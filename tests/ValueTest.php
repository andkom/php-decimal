<?php

use AndKom\Decimal;

class ValueTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $d = new Decimal();
        $this->assertSame($d->getValue(), '0');
    }

    public function testSet()
    {
        $d = new Decimal();
        $d->setValue('123');
        $this->assertSame($d->getValue(), '123');
    }

    public function testToString()
    {
        $this->assertSame((string)(new Decimal('123')), '123');
        $this->assertSame((string)(new Decimal('1.50', 2)), '1.50');
        $this->assertSame((string)(new Decimal('0')), '0');
        $this->assertSame((string)(new Decimal('-5.10', 2)), '-5.10');
    }
}