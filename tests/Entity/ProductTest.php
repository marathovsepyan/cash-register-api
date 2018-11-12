<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 *
 * @package App\Tests\Entity
 */
class ProductTest extends TestCase
{
    public function testSettersImplementFluentInterfacePattern()
    {
        $product = new Product();
        self::assertInstanceOf(Product::class, $product->setBarcode('frde45o'));
        self::assertInstanceOf(Product::class, $product->setName('Product 1'));
        self::assertInstanceOf(Product::class, $product->setCost(100));
        self::assertInstanceOf(Product::class, $product->setVatClass(Product::VAT_6_PERCENT));
        self::assertInstanceOf(Product::class, $product->setCreatedAt(new \DateTime('now')));
    }

    public function testFieldsAreCorrectlyFilled()
    {
        $createdAt = new \DateTime('now');

        $product = new Product();
        $product
            ->setBarcode('frde45o')
            ->setName('Product 1')
            ->setCost(100)
            ->setVatClass(Product::VAT_21_PERCENT)
            ->setCreatedAt($createdAt);

        self::assertEquals('frde45o', $product->getBarcode());
        self::assertEquals('Product 1', $product->getName());
        self::assertEquals(100, $product->getCost());
        self::assertEquals(Product::VAT_21_PERCENT, $product->getVatClass());
        self::assertEquals($createdAt, $product->getCreatedAt());
    }
}
