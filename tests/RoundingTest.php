<?php

use AndKom\Decimal;

class RoundingTest extends \PHPUnit\Framework\TestCase
{
    public function testFloor()
    {
        $this->assertSame((new Decimal('0', 1))->floor()->getValue(), '0');

        $this->assertSame((new Decimal('1', 1))->floor()->getValue(), '1');
        $this->assertSame((new Decimal('1.2', 1))->floor()->getValue(), '1');
        $this->assertSame((new Decimal('1.8', 1))->floor()->getValue(), '1');

        $this->assertSame((new Decimal('-1', 1))->floor()->getValue(), '-1');
        $this->assertSame((new Decimal('-1.2', 1))->floor()->getValue(), '-2');
        $this->assertSame((new Decimal('-1.8', 1))->floor()->getValue(), '-2');

        // with precision
        $this->assertSame((new Decimal('1.23', 2))->floor(1)->getValue(), '1.2');
        $this->assertSame((new Decimal('-1.23', 2))->floor(1)->getValue(), '-1.3');
        $this->assertSame((new Decimal('123'))->floor(-1)->getValue(), '120');
        $this->assertSame((new Decimal('-123'))->floor(-1)->getValue(), '-130');
    }

    public function testCeil()
    {
        $this->assertSame((new Decimal('0', 1))->ceil()->getValue(), '0');

        $this->assertSame((new Decimal('1', 1))->ceil()->getValue(), '1');
        $this->assertSame((new Decimal('1.2', 1))->ceil()->getValue(), '2');
        $this->assertSame((new Decimal('1.8', 1))->ceil()->getValue(), '2');

        $this->assertSame((new Decimal('-1', 1))->ceil()->getValue(), '-1');
        $this->assertSame((new Decimal('-1.2', 1))->ceil()->getValue(), '-1');
        $this->assertSame((new Decimal('-1.8', 1))->ceil()->getValue(), '-1');

        // with precision
        $this->assertSame((new Decimal('1.23', 2))->ceil(1)->getValue(), '1.3');
        $this->assertSame((new Decimal('-1.23', 2))->ceil(1)->getValue(), '-1.2');
        $this->assertSame((new Decimal('123'))->ceil(-1)->getValue(), '130');
        $this->assertSame((new Decimal('-123'))->ceil(-1)->getValue(), '-120');
    }

    public function testTruncate()
    {
        $this->assertSame((new Decimal('1.1', 1))->truncate(0)->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->truncate(1)->getValue(), '1.1');
        $this->assertSame((new Decimal('1.12', 1))->truncate(1)->getValue(), '1.1');

        $this->assertSame((new Decimal('1'))->truncate(0)->getValue(), '1');
        $this->assertSame((new Decimal('1'))->truncate(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1'))->truncate(-1)->getValue(), '0');
        $this->assertSame((new Decimal('10'))->truncate(-1)->getValue(), '10');
        $this->assertSame((new Decimal('12'))->truncate(-1)->getValue(), '10');
        $this->assertSame((new Decimal('15'))->truncate(-1)->getValue(), '10');
        $this->assertSame((new Decimal('18'))->truncate(-1)->getValue(), '10');
        $this->assertSame((new Decimal('123'))->truncate(-1)->getValue(), '120');
        $this->assertSame((new Decimal('123'))->truncate(-2)->getValue(), '100');

        $this->assertSame((new Decimal('-1.1', 1))->truncate(1)->getValue(), '-1.1');
        $this->assertSame((new Decimal('-1.1', 1))->truncate()->getValue(), '-1');
        $this->assertSame((new Decimal('-123'))->truncate(-2)->getValue(), '-100');

        // positive precision > 1
        $this->assertSame((new Decimal('1.789', 3))->truncate(2)->getValue(), '1.78');
        $this->assertSame((new Decimal('1.789', 3))->truncate(3)->getValue(), '1.789');
        $this->assertSame((new Decimal('123.456789', 6))->truncate(2)->getValue(), '123.45');
        $this->assertSame((new Decimal('123.456789', 6))->truncate(4)->getValue(), '123.4567');
        $this->assertSame((new Decimal('-9.876', 3))->truncate(2)->getValue(), '-9.87');
    }

    public function testRoundUp()
    {
        $this->assertSame((new Decimal('1.0', 1))->roundUp()->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->roundUp()->getValue(), '2');
        $this->assertSame((new Decimal('1.5', 1))->roundUp()->getValue(), '2');
        $this->assertSame((new Decimal('1.9', 1))->roundUp()->getValue(), '2');

        $this->assertSame((new Decimal('1'))->roundUp(0)->getValue(), '1');
        $this->assertSame((new Decimal('1'))->roundUp(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1'))->roundUp(-1)->getValue(), '10');

        $this->assertSame((new Decimal('123'))->roundUp(0)->getValue(), '123');
        $this->assertSame((new Decimal('120'))->roundUp(-1)->getValue(), '120');
        $this->assertSame((new Decimal('123'))->roundUp(-1)->getValue(), '130');
        $this->assertSame((new Decimal('100'))->roundUp(-2)->getValue(), '100');
        $this->assertSame((new Decimal('123'))->roundUp(-2)->getValue(), '200');

        $this->assertSame((new Decimal('-1.1', 1))->roundUp(1)->getValue(), '-1.1');
        $this->assertSame((new Decimal('-1.1', 1))->roundUp()->getValue(), '-1');
        $this->assertSame((new Decimal('-123'))->roundUp(-2)->getValue(), '-100');
    }

    public function testRoundDown()
    {
        $this->assertSame((new Decimal('1.0', 1))->roundDown()->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->roundDown()->getValue(), '1');
        $this->assertSame((new Decimal('1.5', 1))->roundDown()->getValue(), '1');
        $this->assertSame((new Decimal('1.9', 1))->roundDown()->getValue(), '1');

        $this->assertSame((new Decimal('1'))->roundDown(0)->getValue(), '1');
        $this->assertSame((new Decimal('1'))->roundDown(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1'))->roundDown(-1)->getValue(), '0');

        $this->assertSame((new Decimal('123'))->roundDown(0)->getValue(), '123');
        $this->assertSame((new Decimal('120'))->roundDown(-1)->getValue(), '120');
        $this->assertSame((new Decimal('123'))->roundDown(-1)->getValue(), '120');
        $this->assertSame((new Decimal('100'))->roundDown(-2)->getValue(), '100');
        $this->assertSame((new Decimal('123'))->roundDown(-2)->getValue(), '100');
    }

    public function testRoundHalfUp()
    {
        $this->assertSame((new Decimal('1.0', 1))->roundHalfUp()->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->roundHalfUp()->getValue(), '1');
        $this->assertSame((new Decimal('1.5', 1))->roundHalfUp()->getValue(), '2');
        $this->assertSame((new Decimal('1.9', 1))->roundHalfUp()->getValue(), '2');

        $this->assertSame((new Decimal('1.01', 2))->roundHalfUp(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1.05', 2))->roundHalfUp(1)->getValue(), '1.1');

        $this->assertSame((new Decimal('1'))->roundHalfUp(0)->getValue(), '1');
        $this->assertSame((new Decimal('1'))->roundHalfUp(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1'))->roundHalfUp(-1)->getValue(), '0');

        $this->assertSame((new Decimal('123'))->roundHalfUp(0)->getValue(), '123');
        $this->assertSame((new Decimal('120'))->roundHalfUp(-1)->getValue(), '120');
        $this->assertSame((new Decimal('123'))->roundHalfUp(-1)->getValue(), '120');
        $this->assertSame((new Decimal('125'))->roundHalfUp(-1)->getValue(), '130');
        $this->assertSame((new Decimal('100'))->roundHalfUp(-2)->getValue(), '100');
        $this->assertSame((new Decimal('123'))->roundHalfUp(-2)->getValue(), '100');
        $this->assertSame((new Decimal('163'))->roundHalfUp(-2)->getValue(), '200');

        // negative values (ties away from zero)
        $this->assertSame((new Decimal('-1.5', 1))->roundHalfUp()->getValue(), '-2');
        $this->assertSame((new Decimal('-1.4', 1))->roundHalfUp()->getValue(), '-1');
        $this->assertSame((new Decimal('-1.6', 1))->roundHalfUp()->getValue(), '-2');
    }

    public function testRoundHalfDown()
    {
        $this->assertSame((new Decimal('1.0', 1))->roundHalfDown()->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->roundHalfDown()->getValue(), '1');
        $this->assertSame((new Decimal('1.5', 1))->roundHalfDown()->getValue(), '1');
        $this->assertSame((new Decimal('1.9', 1))->roundHalfDown()->getValue(), '2');

        $this->assertSame((new Decimal('1.01', 2))->roundHalfDown(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1.05', 2))->roundHalfDown(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1.06', 2))->roundHalfDown(1)->getValue(), '1.1');

        $this->assertSame((new Decimal('125'))->roundHalfDown(-1)->getValue(), '120');
        $this->assertSame((new Decimal('126'))->roundHalfDown(-1)->getValue(), '130');

        // negative values (ties toward zero)
        $this->assertSame((new Decimal('-1.5', 1))->roundHalfDown()->getValue(), '-1');
        $this->assertSame((new Decimal('-1.6', 1))->roundHalfDown()->getValue(), '-2');
    }

    public function testRoundHalfEven()
    {
        $this->assertSame((new Decimal('1.0', 1))->roundHalfEven()->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->roundHalfEven()->getValue(), '1');
        $this->assertSame((new Decimal('1.5', 1))->roundHalfEven()->getValue(), '2');
        $this->assertSame((new Decimal('1.9', 1))->roundHalfEven()->getValue(), '2');

        $this->assertSame((new Decimal('2.0', 1))->roundHalfEven()->getValue(), '2');
        $this->assertSame((new Decimal('2.1', 1))->roundHalfEven()->getValue(), '2');
        $this->assertSame((new Decimal('2.5', 1))->roundHalfEven()->getValue(), '2');
        $this->assertSame((new Decimal('2.9', 1))->roundHalfEven()->getValue(), '3');

        $this->assertSame((new Decimal('1.11', 2))->roundHalfEven(1)->getValue(), '1.1');
        $this->assertSame((new Decimal('1.15', 2))->roundHalfEven(1)->getValue(), '1.2');

        $this->assertSame((new Decimal('1.21', 2))->roundHalfEven(1)->getValue(), '1.2');
        $this->assertSame((new Decimal('1.25', 2))->roundHalfEven(1)->getValue(), '1.2');

        $this->assertSame((new Decimal('1'))->roundHalfEven(0)->getValue(), '1');
        $this->assertSame((new Decimal('1'))->roundHalfEven(1)->getValue(), '1.0');
        $this->assertSame((new Decimal('1'))->roundHalfEven(-1)->getValue(), '0');

        $this->assertSame((new Decimal('123'))->roundHalfEven(0)->getValue(), '123');
        $this->assertSame((new Decimal('120'))->roundHalfEven(-1)->getValue(), '120');
        $this->assertSame((new Decimal('123'))->roundHalfEven(-1)->getValue(), '120');
        $this->assertSame((new Decimal('125'))->roundHalfEven(-1)->getValue(), '120');
        $this->assertSame((new Decimal('135'))->roundHalfEven(-1)->getValue(), '140');
        $this->assertSame((new Decimal('100'))->roundHalfEven(-2)->getValue(), '100');
        $this->assertSame((new Decimal('123'))->roundHalfEven(-2)->getValue(), '100');
        $this->assertSame((new Decimal('163'))->roundHalfEven(-2)->getValue(), '200');

        // negative values
        $this->assertSame((new Decimal('-1.5', 1))->roundHalfEven()->getValue(), '-2');
        $this->assertSame((new Decimal('-2.5', 1))->roundHalfEven()->getValue(), '-2');
        $this->assertSame((new Decimal('-1.9', 1))->roundHalfEven()->getValue(), '-2');
    }

    public function testRoundHalfOdd()
    {
        $this->assertSame((new Decimal('1.0', 1))->roundHalfOdd()->getValue(), '1');
        $this->assertSame((new Decimal('1.1', 1))->roundHalfOdd()->getValue(), '1');
        $this->assertSame((new Decimal('1.5', 1))->roundHalfOdd()->getValue(), '1');
        $this->assertSame((new Decimal('1.9', 1))->roundHalfOdd()->getValue(), '2');

        $this->assertSame((new Decimal('2.0', 1))->roundHalfOdd()->getValue(), '2');
        $this->assertSame((new Decimal('2.1', 1))->roundHalfOdd()->getValue(), '2');
        $this->assertSame((new Decimal('2.5', 1))->roundHalfOdd()->getValue(), '3');
        $this->assertSame((new Decimal('2.9', 1))->roundHalfOdd()->getValue(), '3');

        $this->assertSame((new Decimal('1.15', 2))->roundHalfOdd(1)->getValue(), '1.1');
        $this->assertSame((new Decimal('1.25', 2))->roundHalfOdd(1)->getValue(), '1.3');

        $this->assertSame((new Decimal('125'))->roundHalfOdd(-1)->getValue(), '130');
        $this->assertSame((new Decimal('135'))->roundHalfOdd(-1)->getValue(), '130');

        // negative values
        $this->assertSame((new Decimal('-1.5', 1))->roundHalfOdd()->getValue(), '-1');
        $this->assertSame((new Decimal('-2.5', 1))->roundHalfOdd()->getValue(), '-3');
    }

    public function testRound()
    {
        $this->assertSame((new Decimal('1.5', 1))->round()->getValue(), '2');
        $this->assertSame((new Decimal('1.45', 2))->round(1)->getValue(), '1.5');
        $this->assertSame((new Decimal('1.45', 2))->round(2)->getValue(), '1.45');
    }
}
