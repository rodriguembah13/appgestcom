<?php

namespace App\Entity\Models;

class OrderCustomer
{
    private $customer;
    private $entrepot;
    private $datelivraison;

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
