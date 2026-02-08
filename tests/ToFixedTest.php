<?php

namespace AndKom\Tests;

use AndKom\Decimal;

class ToFixedTest extends \PHPUnit\Framework\TestCase
{
   public function testToFixed()
    {
        $this->assertSame((new Decimal('1.2345', 4))->toFixed(2), '1.23');
        $this->assertSame((new Decimal('1.2345', 4))->toFixed(0), '1');
        $this->assertSame((new Decimal('1', 0))->toFixed(3), '1.000');
        $this->assertSame((new Decimal('-1.5', 1))->toFixed(0), '-1');
        $this->assertSame((new Decimal('0'))->toFixed(2), '0.00');
    }

    public function testToFixedDoesNotMutate()
    {
        $d = new Decimal('1.2345', 4);
        $d->toFixed(2);
        $this->assertSame($d->getValue(), '1.2345');
        $this->assertSame($d->getScale(), 4);
    }
}