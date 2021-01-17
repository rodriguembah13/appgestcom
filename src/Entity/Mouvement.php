<?php

namespace App\Entity;

use App\Util\DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MouvementRepository")
 */
class Mouvement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=200, nullable=true)
     * @Assert\Length(min=2, max=200)
     */
    private $libelle;
    /**
     * @var boolean
     *
     * @ORM\Column(name="in_stock", type="boolean")
     */
    private $in_stock = false;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $out_quantity = 0;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $in_quantity = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MouvementLine", mappedBy="mouvement")
     */
    private $mouvementLines;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entrepot")
     */
    private $entrepot;

    /**
     * Stock constructor.
     */
    public function __construct()
    {
        $this->createdAt = DateTime::getDateTime();
        $this->updatedAt = DateTime::getDateTime();
        $this->mouvementLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutQuantity(): int
    {
        return $this->out_quantity;
    }

    public function setOutQuantity(int $out_quantity): void
    {
        $this->out_quantity = $out_quantity;
    }

    public function getInQuantity(): int
    {
        return $this->in_quantity;
    }

    public function setInQuantity(int $in_quantity): void
    {
        $this->in_quantity = $in_quantity;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection|MouvementLine[]
     */
    public function getMouvementLines(): Collection
    {
        return $this->mouvementLines;
    }

    public function addMouvementLine(MouvementLine $mouvementLine): self
    {
        if (!$this->mouvementLines->contains($mouvementLine)) {
            $this->mouvementLines[] = $mouvementLine;
            $mouvementLine->setMouvement($this);
        }

        return $this;
    }

    public function removeMouvementLine(MouvementLine $mouvementLine): self
    {
        if ($this->mouvementLines->contains($mouvementLine)) {
            $this->mouvementLines->removeElement($mouvementLine);
            // set the owning side to null (unless already changed)
            if ($mouvementLine->getMouvement() === $this) {
                $mouvementLine->setMouvement(null);
            }
        }

        return $this;
    }

    public function getLibelle():? string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
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
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->in_stock;
    }

    /**
     * @param bool $in_stock
     */
    public function setInStock(bool $in_stock): void
    {
        $this->in_stock = $in_stock;
    }

}
