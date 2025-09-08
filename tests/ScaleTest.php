<?php

use AndKom\Decimal;

class ScaleTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $d = new Decimal();
        $this->assertSame($d->getScale(), 0);
    }

    public function testSet()
    {
        $d = new Decimal();
        $d->setScale(2);
        $this->assertSame($d->getScale(), 2);
        $this->assertSame($d->getValue(), '0.00');
        $d->setScale(0);
        $this->assertSame($d->getValue(), '0');
    }
}