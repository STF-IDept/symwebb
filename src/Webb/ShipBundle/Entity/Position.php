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
     * @ORM\Column(name="custom_long", type="string", length=255)
     */
    private $customLong;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_short", type="string", length=255)
     */
    private $customShort;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Position",  cascade={"persist"})
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Position")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Ship",  cascade={"persist"})
     * @ORM\JoinColumn(name="ship_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Ship")
     * @Assert\NotBlank()
     */
    private $ship;

    /**
     * @ORM\OneToMany(targetEntity="Webb\CharacterBundle\Entity\Assignment", mappedBy="position")
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
     * Set customLong
     *
     * @param string $customLong
     * @return Position
     */
    public function setCustomLong($customLong)
    {
        $this->customLong = $customLong;

        return $this;
    }

    /**
     * Get customLong
     *
     * @return string 
     */
    public function getCustomLong()
    {
        return $this->customLong;
    }

    /**
     * Set customShort
     *
     * @param string $customShort
     * @return Position
     */
    public function setCustomShort($customShort)
    {
        $this->customShort = $customShort;

        return $this;
    }

    /**
     * Get customShort
     *
     * @return string
     */
    public function getCustomShort()
    {
        return $this->customShort;
    }

    public function getLongName() {
        if(empty($this->customLong)) {
            return $this->parent->getLongName();
        }
        else {
            return $this->customLong;
        }
    }

    public function getShortName() {
        if(empty($this->customShort)) {
            return $this->parent->getShortName();
        }
        else {
            return $this->customShort;
        }
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

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
     * Truncate assignment
     *
     * @return Position
     */
    public function truncateAssignment()
    {
        if(is_array($this->assignment)) {
            $this->assignment = array_slice($this->assignment, 0, 1);
        }

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

    public function __toString()
    {
        return $this->getLongName();
    }
}
