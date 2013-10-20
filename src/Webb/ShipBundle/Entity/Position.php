<?php

namespace Webb\ShipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Position
 *
 * @ORM\Table(name="ship_positions")
 * @ORM\Entity
 */
class Position
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
     * @ORM\Column(name="custom", type="string", length=255)
     */
    private $custom;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Position",  cascade={"persist"})
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Position")
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="Ship",  cascade={"persist"})
     * @ORM\JoinColumn(name="ship_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Ship")
     * @Assert\NotBlank()
     */
    private $ship;

    /**
     * @ORM\OneToOne(targetEntity="Webb\CharacterBundle\Entity\Assignment", mappedBy="position")
     */
    protected $assignment;

    /**
     * @ORM\OneToMany(targetEntity="Webb\MotdBundle\Entity\Box", mappedBy="position")
     */
    protected $box;

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
     * Set custom
     *
     * @param string $custom
     * @return Position
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * Get custom
     *
     * @return string 
     */
    public function getCustom()
    {
        return $this->custom;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assignment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add assignment
     *
     * @param \Webb\CharacterBundle\Entity\Assignment $assignment
     * @return Position
     */
    public function addAssignment(\Webb\CharacterBundle\Entity\Assignment $assignment)
    {
        $this->assignment[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \Webb\CharacterBundle\Entity\Assignment $assignment
     */
    public function removeAssignment(\Webb\CharacterBundle\Entity\Assignment $assignment)
    {
        $this->assignment->removeElement($assignment);
    }

    /**
     * Get assignment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * Set assignment
     *
     * @param \Webb\CharacterBundle\Entity\Assignment $assignment
     * @return Position
     */
    public function setAssignment(\Webb\CharacterBundle\Entity\Assignment $assignment = null)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Add box
     *
     * @param \Webb\MotdBundle\Entity\Box $box
     * @return Position
     */
    public function addBox(\Webb\MotdBundle\Entity\Box $box)
    {
        $this->box[] = $box;

        return $this;
    }

    /**
     * Remove box
     *
     * @param \Webb\MotdBundle\Entity\Box $box
     */
    public function removeBox(\Webb\MotdBundle\Entity\Box $box)
    {
        $this->box->removeElement($box);
    }

    /**
     * Get box
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBox()
    {
        return $this->box;
    }
}
