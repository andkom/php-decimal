<?php

use AndKom\Decimal;

class OperatorsTest extends \PHPUnit\Framework\TestCase
{
    public function testAdd()
    {
        $this->assertSame((new Decimal('1'))->add('2')->getValue(), '3');
        $this->assertSame((new Decimal('0'))->add('0')->getValue(), '0');
        $this->assertSame((new Decimal('-1'))->add('1')->getValue(), '0');
        $this->assertSame((new Decimal('1.5', 1))->add('2.3')->getValue(), '3.8');
        $this->assertSame((new Decimal('1.5', 2))->add('2.3')->getValue(), '3.80');
        $this->assertSame((new Decimal('999999999'))->add('1')->getValue(), '1000000000');

        // with explicit scale
        $this->assertSame((new Decimal('1'))->add('0.5', 1)->getValue(), '1.5');
        $this->assertSame((new Decimal('1.123', 3))->add('0', 1)->getValue(), '1.1');
    }

    public function testSubtract()
    {
        $this->assertSame((new Decimal('5'))->subtract('3')->getValue(), '2');
        $this->assertSame((new Decimal('0'))->subtract('0')->getValue(), '0');
        $this->assertSame((new Decimal('1'))->subtract('3')->getValue(), '-2');
        $this->assertSame((new Decimal('5.5', 1))->subtract('2.3')->getValue(), '3.2');
        $this->assertSame((new Decimal('-1'))->subtract('-3')->getValue(), '2');

        // with explicit scale
        $this->assertSame((new Decimal('1'))->subtract('0.5', 1)->getValue(), '0.5');
    }

    public function testMultiply()
    {
        $this->assertSame((new Decimal('2'))->multiply('3')->getValue(), '6');
        $this->assertSame((new Decimal('0'))->multiply('999')->getValue(), '0');
        $this->assertSame((new Decimal('-2'))->multiply('3')->getValue(), '-6');
        $this->assertSame((new Decimal('-2'))->multiply('-3')->getValue(), '6');
        $this->assertSame((new Decimal('1.5', 1))->multiply('2')->getValue(), '3.0');
        $this->assertSame((new Decimal('1.5', 2))->multiply('1.5')->getValue(), '2.25');

        // with explicit scale
        $this->assertSame((new Decimal('1'))->multiply('3', 2)->getValue(), '3.00');
        $this->assertSame((new Decimal('10'))->multiply('0.1', 1)->getValue(), '1.0');
    }

    public function testDivide()
    {
        $this->assertSame((new Decimal('6'))->divide('3')->getValue(), '2');
        $this->assertSame((new Decimal('0'))->divide('5')->getValue(), '0');
        $this->assertSame((new Decimal('-6'))->divide('3')->getValue(), '-2');
        $this->assertSame((new Decimal('1'))->divide('3', 3)->getValue(), '0.333');
        $this->assertSame((new Decimal('10'))->divide('4', 2)->getValue(), '2.50');
        $this->assertSame((new Decimal('7', 2))->divide('2')->getValue(), '3.50');
        $this->assertSame((new Decimal('1'))->divide('8', 3)->getValue(), '0.125');
    }

    public function testModulus()
    {
        $this->assertSame((new Decimal('10'))->modulus('3')->getValue(), '1');
        $this->assertSame((new Decimal('10'))->modulus('5')->getValue(), '0');
        $this->assertSame((new Decimal('7'))->modulus('4')->getValue(), '3');
        $this->assertSame((new Decimal('-7'))->modulus('4')->getValue(), '-3');
        $this->assertSame((new Decimal('0'))->modulus('5')->getValue(), '0');
    }

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

    public function testSquareRoot()
    {
        $this->assertSame((new Decimal('0'))->squareRoot()->getValue(), '0');
        $this->assertSame((new Decimal('1'))->squareRoot()->getValue(), '1');
        $this->assertSame((new Decimal('4'))->squareRoot()->getValue(), '2');
        $this->assertSame((new Decimal('9'))->squareRoot()->getValue(), '3');
        $this->assertSame((new Decimal('2'))->squareRoot(4)->getValue(), '1.4142');
        $this->assertSame((new Decimal('100'))->squareRoot()->getValue(), '10');
        $this->assertSame((new Decimal('2.25', 2))->squareRoot()->getValue(), '1.50');
    }
}
