<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ColunmTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     * @Assert\Length(min=2, max=50)
     */
    private $name;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateborn", type="date", nullable=true)
     */
    private $dateborn;
    /**
     * @var string
     *
     * @ORM\Column(name="lieunaissance", type="string", length=50, nullable=true)
     * @Assert\Length(min=2, max=50)
     */
    private $lieunaissance;
    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=50, nullable=true)
     * @Assert\Length(min=2, max=20)
     */
    private $sexe;
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50, nullable=true)
     * @Assert\Length(min=2, max=20)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=true)
     * @Assert\Length(min=2, max=50)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @return mixed|null
     */
    public function getName(): string
    {
        if (null === $this->name) {
            return ' ';
        }

        return strtolower($this->name);
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     */
    public function setImageFilename($imageFilename): void
    {
        $this->imageFilename = $imageFilename;
    }

    public function getDateborn(): ?\DateTime
    {
        return $this->dateborn;
    }

    public function setDateborn(\DateTime $dateborn = null): void
    {
        $this->dateborn = $dateborn;
    }

    /**
     * @return mixed|null
     */
    public function getLieunaissance():? string
    {
        if (null === $this->lieunaissance) {
            return ' ';
        }

        return strtolower($this->lieunaissance);
    }

    public function setLieunaissance(?string $lieunaissance): void
    {
        $this->lieunaissance = $lieunaissance;
    }

    public function getSexe():? string
    {
        if (null === $this->sexe) {
            return ' ';
        }

        return strtolower($this->sexe);
    }

    public function setSexe(?string $sexe): void
    {
        $this->sexe = $sexe;
    }

    /**
     * @return mixed|null
     */
    public function getAdresse()
    {
        if (null === $this->adresse) {
            return ' ';
        }

        return strtolower($this->adresse);
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getPhone(): ? string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
