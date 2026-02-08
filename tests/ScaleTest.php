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

    public function testSetDefaultScale()
    {
        // set default and verify new decimals use it
        Decimal::setDefaultScale(3);
        $this->assertSame(Decimal::getDefaultScale(), 3);
        $this->assertSame((new Decimal('1'))->getScale(), 3);
        $this->assertSame((new Decimal('1'))->getValue(), '1.000');

        // explicit scale overrides default
        $this->assertSame((new Decimal('1', 1))->getScale(), 1);

        // change default
        Decimal::setDefaultScale(0);
        $this->assertSame(Decimal::getDefaultScale(), 0);
        $this->assertSame((new Decimal('1'))->getScale(), 0);
        $this->assertSame((new Decimal('1'))->getValue(), '1');

        // reset to null
        Decimal::setDefaultScale(null);
    }
}