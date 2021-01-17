<?php

namespace App\Entity\Models;

class OrderSupply
{
    private $supply;
    private $entrepot;
    private $datelivraison;

    /**
     * @return mixed
     */
    public function getSupply()
    {
        return $this->supply;
    }

    /**
     * @param mixed $supply
     */
    public function setSupply($supply): void
    {
        $this->supply = $supply;
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
    public function getDatelivraison()
    {
        return $this->datelivraison;
    }

    /**
     * @param mixed $datelivraison
     */
    public function setDatelivraison($datelivraison): void
    {
        $this->datelivraison = $datelivraison;
    }

}
