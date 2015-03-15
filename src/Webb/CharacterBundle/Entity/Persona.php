<?php

namespace Webb\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Webb\UserBundle\Entity\User as User;
use Webb\FileBundle\Entity\Image as Image;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Persona
 *
 * @ORM\Table(name="character_personas")
 * @ORM\Entity
 */
class Persona
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="species", type="string", length=50)
     */
    protected $species;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="string", length=10)
     */
    protected $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="string", length=10)
     */
    protected $height;

    /**
     * @var string
     *
     * @ORM\Column(name="age", type="string", length=10)
     */
    protected $age;

    /**
     * @ORM\ManyToOne(targetEntity="Rank",  cascade={"persist"})
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Rank")
     */
    protected $rank;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\UserBundle\Entity\User",  cascade={"persist"}, inversedBy="persona")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text")
     */
    protected $bio;

    /**
     * @ORM\OneToOne(cascade={"persist", "remove"}, targetEntity="Webb\FileBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="set null")
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Webb\CharacterBundle\Entity\Assignment", mappedBy="persona")
     */
    protected $assignment;

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
     * @return Persona
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
     * Set species
     *
     * @param string $species
     * @return Persona
     */
    public function setSpecies($species)
    {
        $this->species = $species;
    
        return $this;
    }

    /**
     * Get species
     *
     * @return string 
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set weight
     *
     * @param string $weight
     * @return Persona
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    
        return $this;
    }

    /**
     * Get weight
     *
     * @return string 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param string $height
     * @return Persona
     */
    public function setHeight($height)
    {
        $this->height = $height;
    
        return $this;
    }

    /**
     * Get height
     *
     * @return string 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set age
     *
     * @param string $age
     * @return Persona
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function setRank(Rank $rank = null)
    {
        $this->rank = $rank;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Set bio
     *
     * @param string $bio
     * @return Persona
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set image
     *
     * @param Image $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
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
    public function addAssignment(Assignment $assignment)
    {
        $this->assignment[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \Webb\CharacterBundle\Entity\Assignment $assignment
     */
    public function removeAssignment(Assignment $assignment)
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
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

}
