<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $barcode
     *
     * @return Product|null
     */
    public function findOneByBarcode(string $barcode)
    {
        $product = $this->findOneBy(['barcode' => $barcode]);

        return $product;
    }

    /**
     * @param string $name
     *
     * @return Product|null
     */
    public function findOneByName(string $name)
    {
        $product = $this->findOneBy(['name' => $name]);

        return $product;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Product[]
     */
    public function getProducts(int $offset, int $limit)
    {
        $products = $this->findBy([], [], $limit, $offset);

        return $products;
    }
}
