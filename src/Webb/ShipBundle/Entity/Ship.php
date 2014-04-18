<?php

namespace Webb\ShipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ship
 *
 * @ORM\Table(name="ship_ships")
 * @ORM\Entity
 */
class Ship
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=50)
     */
    private $shortname;

    /**
     * @ORM\OneToOne(targetEntity="Type",  cascade={"persist"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Type")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity="Speed",  cascade={"persist"})
     * @ORM\JoinColumn(name="speed_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Speed")
     * @Assert\NotBlank()
     */
    private $speed;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepts_new", type="boolean")
     */
    private $acceptsNew;

    /**
     * @ORM\OneToOne(targetEntity="Fleet",  cascade={"persist"})
     * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Fleet")
     * @Assert\NotBlank()
     */
    private $fleet;

    /**
     * @ORM\OneToOne(targetEntity="Webb\MotdBundle\Entity\Style",  cascade={"persist"})
     * @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\MotdBundle\Entity\Style")
     * @Assert\NotBlank()
     */
    private $style;

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
     * Set name
     *
     * @param string $name
     * @return Ship
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     * @return Ship
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    
        return $this;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Ship
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set speed
     *
     * @param string $speed
     * @return Ship
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
     * @return Ship
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
     * Set acceptsNew
     *
     * @param boolean $acceptsNew
     * @return Ship
     */
    public function setAcceptsNew($acceptsNew)
    {
        $this->acceptsNew = $acceptsNew;
    
        return $this;
    }

    /**
     * Get acceptsNew
     *
     * @return boolean 
     */
    public function getAcceptsNew()
    {
        return $this->acceptsNew;
    }

    public function getFleet()
    {
        return $this->fleet;
    }

    public function setFleet($fleet)
    {
        $this->fleet = $fleet;

        return $this;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }
}
