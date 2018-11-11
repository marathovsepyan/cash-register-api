<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Receipt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Receipt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Receipt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Receipt[]    findAll()
 * @method Receipt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptRepository extends ServiceEntityRepository
{
    /**
     * ReceiptRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Receipt::class);
    }

    /**
     * @param int $id
     *
     * @return Receipt
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Receipt
    {
        $receipt = $this->find($id);
        if (is_null($receipt)) {
            throw new EntityNotFoundException('Receipt with given id could not be found');
        }

        return $receipt;
    }
}
