<?php

namespace App\Event;

use App\Entity\Entrepot;
use Symfony\Contracts\EventDispatcher\Event;

class EntrepotListEvent extends Event
{
    protected $entrepots = [];
    /**
     * @var int
     */
    protected $max;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @param int $max Maximun number of tasks displayed in panel
     */
    public function __construct($max = null)
    {
        $this->max = $max;
    }

    /**
     * Get the maximun number of notifications displayed in panel.
     *
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @return array
     */
    public function getEntrepots()
    {
        if (null !== $this->max) {
            return array_slice($this->entrepots, 0, $this->max);
        }

        return $this->entrepots;
    }

    /**
     * @return $this
     */
    public function addEntrepot(Entrepot $entrepot)
    {
        $this->entrepots[] = $entrepot;

        return $this;
    }

    /**
     * @param int $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return 0 === $this->total ? count($this->entrepots) : $this->total;
    }
}
