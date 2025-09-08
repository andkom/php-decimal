<?php

use AndKom\Decimal;

class ScientificTest extends \PHPUnit\Framework\TestCase
{
    public function testToScientific()
    {
        $this->assertSame((new Decimal('0'))->toScientific(), '0E0');

        $this->assertSame((new Decimal('1'))->toScientific(), '1E0');
        $this->assertSame((new Decimal('10'))->toScientific(), '1E1');
        $this->assertSame((new Decimal('10000'))->toScientific(), '1E4');

        $this->assertSame((new Decimal('-1'))->toScientific(), '-1E0');
        $this->assertSame((new Decimal('-10'))->toScientific(), '-1E1');
        $this->assertSame((new Decimal('-10000'))->toScientific(), '-1E4');

        $this->assertSame((new Decimal('0.1', 1))->toScientific(), '1E-1');
        $this->assertSame((new Decimal('0.0001', 4))->toScientific(), '1E-4');
    }
}