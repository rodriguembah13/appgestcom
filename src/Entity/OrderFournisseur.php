<?php

namespace App\Entity;

use App\Util\DateTime;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="orderfournisseurs"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrderFournisseurRepository")
 */
class OrderFournisseur
{
    const DEFAULT_STATUS = 'ENCOURS';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    use TraitOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fournisseur")
     */
    private $fournisseur;
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
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * @param mixed $fournisseur
     */
    public function setFournisseur($fournisseur): void
    {
        $this->fournisseur = $fournisseur;
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
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * @param mixed $dateLivraison
     */
    public function setDateLivraison($dateLivraison): void
    {
        $this->dateLivraison = $dateLivraison;
    }


}
