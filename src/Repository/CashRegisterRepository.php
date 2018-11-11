<?php

namespace App\Repository;

use App\Entity\CashRegister;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CashRegister|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashRegister|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashRegister[]    findAll()
 * @method CashRegister[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashRegisterRepository extends ServiceEntityRepository
{
    /**
     * CashRegisterRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashRegister::class);
    }

    /**
     * @param string $uid
     *
     * @return CashRegister|null
     */
    public function findOneByUid(string $uid)
    {
        $cashRegister = $this->findOneBy(['uid' => $uid]);

        return $cashRegister;
    }

    /**
     * @param string $token
     *
     * @return CashRegister|null
     */
    public function findOneByToken(string $token)
    {
        $cashRegister = $this->findOneBy(['token' => $token]);

        return $cashRegister;
    }
}
