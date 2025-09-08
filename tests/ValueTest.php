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
}