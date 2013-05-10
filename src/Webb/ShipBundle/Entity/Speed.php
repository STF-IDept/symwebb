<?php

namespace Webb\ShipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Speed
 *
 * @ORM\Table(name="ship_speeds")
 * @ORM\Entity
 */
class Speed
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
     * @ORM\Column(name="speed", type="string", length=255)
     */
    private $speed;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="command_limits", type="integer")
     */
    private $commandLimits;

    /**
     * @var integer
     *
     * @ORM\Column(name="dh_limits", type="integer")
     */
    private $dhLimits;

    /**
     * @var integer
     *
     * @ORM\Column(name="jo_limits", type="integer")
     */
    private $joLimits;


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
     * Set speed
     *
     * @param string $speed
     * @return Speed
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    
        return $this;
    }

    /**
     * Get speed
     *
     * @return string 
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Speed
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set commandLimits
     *
     * @param integer $commandLimits
     * @return Speed
     */
    public function setCommandLimits($commandLimits)
    {
        $this->commandLimits = $commandLimits;
    
        return $this;
    }

    /**
     * Get commandLimits
     *
     * @return integer 
     */
    public function getCommandLimits()
    {
        return $this->commandLimits;
    }

    /**
     * Set dhLimits
     *
     * @param integer $dhLimits
     * @return Speed
     */
    public function setDhLimits($dhLimits)
    {
        $this->dhLimits = $dhLimits;
    
        return $this;
    }

    /**
     * Get dhLimits
     *
     * @return integer 
     */
    public function getDhLimits()
    {
        return $this->dhLimits;
    }

    /**
     * Set joLimits
     *
     * @param integer $joLimits
     * @return Speed
     */
    public function setJoLimits($joLimits)
    {
        $this->joLimits = $joLimits;
    
        return $this;
    }

    /**
     * Get joLimits
     *
     * @return integer 
     */
    public function getJoLimits()
    {
        return $this->joLimits;
    }

    /**
     * Return speed
     *
     * @return string
     */
    public function __toString()
    {
        return $this->speed;
    }
}
