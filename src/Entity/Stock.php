<?php

namespace App\Entity;

use App\Util\DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 */
class Stock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entrepot")
     */
    private $entrepot;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $low_stock_quantity = 0;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Stock constructor.
     */
    public function __construct()
    {
        $this->createdAt = DateTime::getDateTime();
        $this->updatedAt = DateTime::getDateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getLowStockQuantity(): int
    {
        return $this->low_stock_quantity;
    }

    /**
     * @param int $low_stock_quantity
     */
    public function setLowStockQuantity(int $low_stock_quantity): void
    {
        $this->low_stock_quantity = $low_stock_quantity;
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
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getEntrepot()
    {
        return $this->entrepot;
    }

    /**
     * @param mixed $entrepot
     */
    public function setEntrepot($entrepot): void
    {
        $this->entrepot = $entrepot;
    }

}
