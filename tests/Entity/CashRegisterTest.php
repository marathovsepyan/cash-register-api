<?php

namespace App\Tests\Entity;

use App\Entity\CashRegister;
use PHPUnit\Framework\TestCase;

/**
 * Class CashRegisterTest
 *
 * @package App\Tests\Entity
 */
class CashRegisterTest extends TestCase
{
    public function testSettersImplementFluentInterfacePattern()
    {
        $cashRegister = new CashRegister();
        self::assertInstanceOf(CashRegister::class, $cashRegister->setToken('token'));
    }

    public function testFieldsAreCorrectlyFilled()
    {
        $cashRegister = new CashRegister();
        $cashRegister->setToken('token');

        self::assertEquals('token', $cashRegister->getToken());
    }
}
