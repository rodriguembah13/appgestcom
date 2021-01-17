<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderLineRepository")
 */
class OrderLine
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
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $total;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxtva;
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $totalTax;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order")
     */
    private $order;

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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getTotalTax()
    {
        return $this->totalTax;
    }

    /**
     * @param mixed $totalTax
     */
    public function setTotalTax($totalTax): void
    {
        $this->totalTax = $totalTax;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
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
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getTauxtva()
    {
        return $this->tauxtva;
    }

    /**
     * @param mixed $tauxtva
     */
    public function setTauxtva($tauxtva): void
    {
        $this->tauxtva = $tauxtva;
    }

}
