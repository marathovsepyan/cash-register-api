<?php

namespace App\Tests\Entity;

use App\Entity\Admin;
use PHPUnit\Framework\TestCase;

/**
 * Class AdminTest
 *
 * @package App\Tests\Entity
 */
class AdminTest extends TestCase
{
    public function testSettersImplementFluentInterfacePattern()
    {
        $admin =  new Admin();
        self::assertInstanceOf(Admin::class, $admin->setToken('token'));
    }

    public function testFieldsAreCorrectlyFilled()
    {
        $admin =  new Admin();
        $admin->setToken('token');

        self::assertEquals('token', $admin->getToken());
    }
}
