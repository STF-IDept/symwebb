<?php

namespace Webb\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Note
 *
 * @ORM\Table(name="notes")
 * @ORM\Entity
 */
class Note
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
     * @ORM\ManyToOne(targetEntity="Location",  cascade={"persist"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\PostBundle\Entity\Location")
     * @Assert\NotBlank()
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="activity", type="string", length=255)
     */
    private $activity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Note",  cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\PostBundle\Entity\Note")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\ShipBundle\Entity\Ship",  cascade={"persist"})
     * @ORM\JoinColumn(name="ship_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Ship")
     * @Assert\NotBlank()
     */
    private $ship;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\UserBundle\Entity\User",  cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\UserBundle\Entity\User")
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Persona",  cascade={"persist"})
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Persona")
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Assignment",  cascade={"persist"})
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Assignment")
     * @Assert\NotBlank()
     */
    private $assignment;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


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
     * @return Note
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
     * Set activity
     *
     * @param string $activity
     * @return Note
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    
        return $this;
    }

    /**
     * Get activity
     *
     * @return string 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set parent
     *
     * @param string $parent
     * @return Note
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return string 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set ship
     *
     * @param string $ship
     * @return Note
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

    /**
     * Set user
     *
     * @param string $user
     * @return Note
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set persona
     *
     * @param string $persona
     * @return Note
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    
        return $this;
    }

    /**
     * Get persona
     *
     * @return string 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    public function getAssignment()
    {
        return $this->assignment;
    }

    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
