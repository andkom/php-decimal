<?php

use AndKom\Decimal;

class CreateTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $this->assertSame((new Decimal())->getValue(), '0');
        $this->assertSame((new Decimal('0'))->getValue(), '0');
        $this->assertSame((new Decimal('-0'))->getValue(), '0');
        $this->assertSame((new Decimal('1.23'))->getValue(), '1');
        $this->assertSame((new Decimal('-1.23'))->getValue(), '-1');
        $this->assertSame((new Decimal(null, 2))->getValue(), '0.00');
        $this->assertSame((new Decimal('0', 2))->getValue(), '0.00');
        $this->assertSame((new Decimal('-0', 2))->getValue(), '0.00');
        $this->assertSame((new Decimal('1.23', 2))->getValue(), '1.23');
        $this->assertSame((new Decimal('-1.23', 2))->getValue(), '-1.23');
    }

    public function testCreateFromString()
    {
        $this->assertSame(Decimal::createFromString('1e0')->getValue(), '1');
        $this->assertSame(Decimal::createFromString('1e1')->getValue(), '10');
        $this->assertSame(Decimal::createFromString('1.0e1')->getValue(), '10');
        $this->assertSame(Decimal::createFromString('1.0e+1')->getValue(), '10');
        $this->assertSame(Decimal::createFromString('1.0e-1')->getValue(), '0.1');
        $this->assertSame(Decimal::createFromString('-1.0e-1')->getValue(), '-0.1');
        $this->assertSame(Decimal::createFromString('+1.0e-1')->getValue(), '0.1');
        $this->assertSame(Decimal::createFromString('1e-8')->getValue(), '0.00000001');
        $this->assertSame(Decimal::createFromString('1e8')->getValue(), '100000000');
        $this->assertSame(Decimal::createFromString('123456789E-9')->getValue(), '0.123456789');
        $this->assertSame(Decimal::createFromString('12345.6789E-5')->getValue(), '0.123456789');
        $this->assertSame(Decimal::createFromString('1E-300')->getValue(), '0.000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001');
    }

    public function testCreateFromFloat()
    {
        // without explicit scale — derives scale from float's natural precision
        $this->assertSame(Decimal::createFromFloat(1.5)->getValue(), '1.5');
        $this->assertSame(Decimal::createFromFloat(0.0)->getValue(), '0');
        $this->assertSame(Decimal::createFromFloat(-1.5)->getValue(), '-1.5');
        $this->assertSame(Decimal::createFromFloat(1.0)->getValue(), '1');
        $this->assertSame(Decimal::createFromFloat(0.123)->getValue(), '0.123');
        $this->assertSame(Decimal::createFromFloat(100.0)->getValue(), '100');

        // with explicit scale
        $this->assertSame(Decimal::createFromFloat(1.5, 3)->getValue(), '1.500');
        $this->assertSame(Decimal::createFromFloat(1.5, 0)->getValue(), '2');
        $this->assertSame(Decimal::createFromFloat(0.0, 2)->getValue(), '0.00');
        $this->assertSame(Decimal::createFromFloat(-1.5, 1)->getValue(), '-1.5');
    }

    public function testCreateFactory()
    {
        // from string
        $this->assertSame(Decimal::create('1.5', 2)->getValue(), '1.50');
        $this->assertSame(Decimal::create('123')->getValue(), '123');

        // from int
        $this->assertSame(Decimal::create(5)->getValue(), '5');
        $this->assertSame(Decimal::create(5, 2)->getValue(), '5.00');
        $this->assertSame(Decimal::create(-3)->getValue(), '-3');

        // from float
        $this->assertSame(Decimal::create(1.5)->getValue(), '1.5');
        $this->assertSame(Decimal::create(1.5, 3)->getValue(), '1.500');

        // from Decimal
        $d = new Decimal('1.23', 2);
        $this->assertSame(Decimal::create($d)->getValue(), '1.23');
        $this->assertSame(Decimal::create($d, 4)->getValue(), '1.2300');

        // from null
        $this->assertSame(Decimal::create(null)->getValue(), '0');
        $this->assertSame(Decimal::create(null, 2)->getValue(), '0.00');
    }

    public function testCreateFromInteger()
    {
        $this->assertSame(Decimal::createFromInteger(0)->getValue(), '0');
        $this->assertSame(Decimal::createFromInteger(123)->getValue(), '123');
        $this->assertSame(Decimal::createFromInteger(-45)->getValue(), '-45');
        $this->assertSame(Decimal::createFromInteger(0, 2)->getValue(), '0.00');
        $this->assertSame(Decimal::createFromInteger(5, 3)->getValue(), '5.000');
    }

    public function testCreateFromDecimal()
    {
        $d = new Decimal('1.23', 2);

        // inherits scale
        $this->assertSame(Decimal::createFromDecimal($d)->getValue(), '1.23');
        $this->assertSame(Decimal::createFromDecimal($d)->getScale(), 2);

        // override scale
        $this->assertSame(Decimal::createFromDecimal($d, 4)->getValue(), '1.2300');
        $this->assertSame(Decimal::createFromDecimal($d, 0)->getValue(), '1');
    }

    public function testInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Decimal('not a number');
    }

    public function testInvalidScale()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Decimal('1', -2);
    }
}