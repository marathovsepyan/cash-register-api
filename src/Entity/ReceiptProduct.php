<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReceiptProduct
 *
 * @ORM\Table(name="receipt_products", indexes={@ORM\Index(name="receipt_id", columns={"receipt_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class ReceiptProduct
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
     * @ORM\Column(name="receipt_id", type="integer", nullable=false)
     */
    private $receiptId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var Receipt
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Receipt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     * })
     */
    private $receipt;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * ReceiptProduct constructor.
     */
    public function __construct()
    {
        $currentDateTime = new \DateTime('now');
        $this->createdAt = $currentDateTime;
        $this->updatedAt = $currentDateTime;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getReceiptId(): int
    {
        return $this->receiptId;
    }

    /**
     * @param int $receiptId
     *
     * @return ReceiptProduct
     */
    public function setReceiptId(int $receiptId): ReceiptProduct
    {
        $this->receiptId = $receiptId;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     *
     * @return ReceiptProduct
     */
    public function setProductId(int $productId): ReceiptProduct
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return ReceiptProduct
     */
    public function setAmount(int $amount): ReceiptProduct
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return ReceiptProduct
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Receipt
     */
    public function getReceipt(): Receipt
    {
        return $this->receipt;
    }

    /**
     * @param Receipt $receipt
     *
     * @return ReceiptProduct
     */
    public function setReceipt(Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return ReceiptProduct
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
