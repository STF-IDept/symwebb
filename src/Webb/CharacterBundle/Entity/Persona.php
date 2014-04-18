<?php

namespace Webb\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Webb\UserBundle\Entity\User as User;

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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="species", type="string", length=50)
     */
    private $species;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="string", length=10)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="string", length=10)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="age", type="string", length=10)
     */
    private $age;

    /**
     * @ORM\OneToOne(targetEntity="Rank",  cascade={"persist"})
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Rank")
     * @Assert\NotBlank()
     */
    protected $rank;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\UserBundle\Entity\User",  cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\UserBundle\Entity\User")
     */
    protected $user;

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

}
