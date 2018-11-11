<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CashRegisters
 *
 * @ORM\Table(name="cash_registers")
 * @ORM\Entity
 */
class CashRegister
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="UID", type="string", length=30, nullable=false, options={"fixed"=true})
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", length=60, nullable=false, options={"fixed"=true})
     */
    private $pwd;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=128, nullable=true, options={"fixed"=true})
     */
    private $token;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getPwd(): string
    {
        return $this->pwd;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return CashRegister
     */
    public function setToken(string $token): CashRegister
    {
        $this->token = $token;

        return $this;
    }
}
