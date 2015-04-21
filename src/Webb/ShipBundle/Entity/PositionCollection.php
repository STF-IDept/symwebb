<?php

namespace Webb\ShipBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class PositionCollection
{

    protected $description;

    protected $positions;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param ArrayCollection $positions
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;
    }
}