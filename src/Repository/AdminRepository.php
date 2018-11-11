<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Admin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admin[]    findAll()
 * @method Admin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminRepository extends ServiceEntityRepository
{
    /**
     * AdminRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    /**
     * @param string $email
     *
     * @return Admin|null
     */
    public function findOneByEmail(string $email)
    {
        $admin = $this->findOneBy(['email' => $email]);

        return $admin;
    }

    /**
     * @param string $token
     *
     * @return Admin|null
     */
    public function findOneByToken(string $token)
    {
        $admin = $this->findOneBy(['token' => $token]);

        return $admin;
    }
}
