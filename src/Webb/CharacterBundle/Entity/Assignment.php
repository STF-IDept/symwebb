<?php

namespace Webb\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="Persona",  cascade={"persist"}, inversedBy="assignment")
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Persona")
     */
    protected $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\ShipBundle\Entity\Position",  cascade={"persist"}, inversedBy="assignment")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Position")
     */
    protected $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     */
    private $enddate;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    public function getTest() {
        return "Test";
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

    /**
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * @param \DateTime $startdate
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * @param \DateTime $enddate
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}
