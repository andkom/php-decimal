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