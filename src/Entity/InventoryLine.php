<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventoryLineRepository")
 */
class InventoryLine
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
    private $real_quantity = 0;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock_quantity = 0;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventory")
     */
    private $inventory;
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRealQuantity(): int
    {
        return $this->real_quantity;
    }

    /**
     * @param int $real_quantity
     */
    public function setRealQuantity(int $real_quantity): void
    {
        $this->real_quantity = $real_quantity;
    }

    /**
     * @return int
     */
    public function getStockQuantity(): int
    {
        return $this->stock_quantity;
    }

    /**
     * @param int $stock_quantity
     */
    public function setStockQuantity(int $stock_quantity): void
    {
        $this->stock_quantity = $stock_quantity;
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
     * @return mixed
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @param mixed $inventory
     */
    public function setInventory($inventory): void
    {
        $this->inventory = $inventory;
    }

}
