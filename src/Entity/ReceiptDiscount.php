<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReceiptDiscount
 *
 * @ORM\Table(name="receipt_discounts", indexes={@ORM\Index(name="receipt_id", columns={"receipt_id"})})
 * @ORM\Entity
 */
class ReceiptDiscount
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
     * @var int
     *
     * @ORM\Column(name="discount_percent", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $discountPercent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \Receipts
     *
     * @ORM\ManyToOne(targetEntity="Receipts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     * })
     */
    private $receipt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(int $discountPercent): self
    {
        $this->discountPercent = $discountPercent;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }


}
