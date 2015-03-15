<?php

namespace Webb\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Location
 *
 * @ORM\Table(name="note_locations")
 * @ORM\Entity
 */
class Location
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\ShipBundle\Entity\Ship",  cascade={"persist"})
     * @ORM\JoinColumn(name="ship_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Ship")
     * @Assert\NotBlank()
     */
    private $ship;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set ship
     *
     * @param string $ship
     * @return Location
     */
    public function setShip($ship)
    {
        $this->ship = $ship;
    
        return $this;
    }

    /**
     * Get ship
     *
     * @return string 
     */
    public function getShip()
    {
        return $this->ship;
    }

    public function __toString()
    {
        return $this->location;
    }
}
