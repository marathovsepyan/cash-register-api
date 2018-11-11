<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products", uniqueConstraints={@ORM\UniqueConstraint(name="barcode", columns={"barcode"})})
 * @ORM\Entity
 */
class Product
{
    const VAT_6_PERCENT = '1';
    const VAT_21_PERCENT = '2';

    const VAT_CLASS_NAMES = [
        'vat_6' => self::VAT_6_PERCENT,
        'vat_21' => self::VAT_21_PERCENT,
    ];

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
     * @ORM\Column(name="barcode", type="string", length=255, nullable=false)
     */
    private $barcode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $cost;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_class", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $vatClass;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

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
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     *
     * @return Product
     */
    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     *
     * @return Product
     */
    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return string
     */
    public function getVatClass(): string
    {
        return $this->vatClass;
    }

    /**
     * @param string $vatClass
     *
     * @return Product
     */
    public function setVatClass(string $vatClass): self
    {
        $this->vatClass = $vatClass;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
