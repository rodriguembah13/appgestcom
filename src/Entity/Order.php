<?php

namespace App\Entity;

use App\Util\DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="orders"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    use TraitOrder;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     */
    private $customer;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entrepot")
     */
    private $entrepot;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateLivraison;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->dateLivraison = DateTime::getDateTime();
        $this->createdAt = DateTime::getDateTime();
        $this->updatedAt = DateTime::getDateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }

    public function __toString()
    {
        return $this->customer;
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

    /**
     * @return \DateTime
     */
    public function getDateLivraison(): ?\DateTime
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTime $dateLivraison): void
    {
        $this->dateLivraison = $dateLivraison;
    }
}
