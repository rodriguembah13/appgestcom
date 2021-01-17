<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderFournisseurLineRepository")
 */
class OrderFournisseurLine
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
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $totalTax;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxremise;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxtva;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalTva;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderFournisseur")
     */
    private $orderFournisseur;


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
     * @return mixed
     */
    public function getOrderFournisseur()
    {
        return $this->orderFournisseur;
    }

    /**
     * @param mixed $orderFournisseur
     */
    public function setOrderFournisseur($orderFournisseur): void
    {
        $this->orderFournisseur = $orderFournisseur;
    }

    /**
     * @return mixed
     */
    public function getTauxremise()
    {
        return $this->tauxremise;
    }

    /**
     * @param mixed $tauxremise
     */
    public function setTauxremise($tauxremise): void
    {
        $this->tauxremise = $tauxremise;
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

    /**
     * @return mixed
     */
    public function getTotalTva()
    {
        return $this->totalTva;
    }

    /**
     * @param mixed $totalTva
     */
    public function setTotalTva($totalTva): void
    {
        $this->totalTva = $totalTva;
    }


}
