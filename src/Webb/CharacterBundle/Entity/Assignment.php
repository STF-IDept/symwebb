<?php

namespace Webb\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Webb\CharacterBundle\Entity\Persona;
/**
 * Assignment
 *
 * @ORM\Table(name="character_assignment")
 * @ORM\Entity
 */
class Assignment
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
     * @var boolean
     *
     * @ORM\Column(name="rostered", type="boolean")
     */
    private $rostered;

    /**
     * @ORM\OneToOne(targetEntity="Persona",  cascade={"persist"})
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Persona")
     */
    protected $persona;

    /**
     * @ORM\OneToOne(targetEntity="Webb\ShipBundle\Entity\Position",  cascade={"persist"}, inversedBy="assignment")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Position")
     */
    protected $position;


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
     * Set rostered
     *
     * @param boolean $rostered
     * @return Assignment
     */
    public function setRostered($rostered)
    {
        $this->rostered = $rostered;
    
        return $this;
    }

    /**
     * Get rostered
     *
     * @return boolean 
     */
    public function getRostered()
    {
        return $this->rostered;
    }

    public function getPersona()
    {
        return $this->persona;
    }

    public function setPersona(Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition(Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    public function getShip()
    {
        return $this->ship;
    }

    public function setShip($ship)
    {
        $this->ship = $ship;

        return $this;
    }

    public function __toString()
    {
        return "{$this->persona->getRank()->getLongName()} {$this->persona->getName()} ({$this->position->getLongName()})";
    }
}
