<?php

namespace App\Repository;

use App\Entity\ReceiptProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReceiptProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceiptProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceiptProduct[]    findAll()
 * @method ReceiptProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptProductRepository extends ServiceEntityRepository
{
    /**
     * ReceiptRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReceiptProduct::class);
    }

    /**
     * @param int $receiptId
     *
     * @return int
     */
    public function getRecipeProductsCount(int $receiptId): int
    {
        $productsCount = $this->count(['receipt' => $receiptId]);

        return $productsCount;
    }

    /**
     * @param int $receiptId
     * @param int $productId
     *
     * @return ReceiptProduct|null
     */
    public function findOneByReceiptIdAndProductId(int $receiptId, int $productId)
    {
        $receiptProduct = $this->findOneBy(['receipt'=> $receiptId, 'product' => $productId]);

        return $receiptProduct;
    }

    /**
     * @param int $recipeId
     *
     * @return ReceiptProduct|null
     */
    public function getRecipeLastProduct(int $recipeId)
    {
        $lastReceiptProduct = $this->findOneBy(['receipt' => $recipeId], ['updatedAt' => 'DESC']);

        return $lastReceiptProduct;
    }

    /**
     * @param int $recipeId
     *
     * @return mixed
     */
    public function getRecipeProductsData(int $recipeId)
    {
        $queryBuilder = $this->createQueryBuilder('rp');
        $recipeProducts = $queryBuilder
            ->select(['rp.productId', 'rp.amount'])
            ->andWhere('rp.receipt = :receipt')
            ->setParameter(':receipt', $recipeId)
            ->orderBy('rp.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        return $recipeProducts;
    }
}
