<?php

namespace Webb\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Application
 *
 * @ORM\Table(name="user_applications")
 * @ORM\Entity
 */
class Application
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
     * @ORM\Column(name="activity_rate", type="integer", columnDefinition="enum('Infrequent', 'Slow', 'Average', 'Regular', 'Frequent')")
     */
    private $activityRate;

    /**
     * @var string
     *
     * @ORM\Column(name="academy_ship", type="integer", columnDefinition="enum('Yes', 'No')")
     */
    private $academyShip;

    /**
     * @var string
     *
     * @ORM\Column(name="mentor_request", type="integer", columnDefinition="enum('Yes', 'No')")
     */
    private $mentorRequest;

    /**
     * @var string
     *
     * @ORM\Column(name="position_first", type="integer", columnDefinition="enum('Engineer', 'Medical', 'Science', 'Security')")
     */
    private $positionFirst;

    /**
     * @var string
     *
     * @ORM\Column(name="position_second", type="integer", columnDefinition="enum('Engineer', 'Medical', 'Science', 'Security')")
     */
    private $positionSecond;

    /**
     * @var string
     *
     * @ORM\Column(name="position_third", type="integer", columnDefinition="enum('Engineer', 'Medical', 'Science', 'Security')")
     */
    private $positionThird;

    /**
     * @var string
     *
     * @ORM\Column(name="hear_about", type="string", length=255)
     */
    private $hearAbout;

    /**
     * @var string
     *
     * @ORM\Column(name="character_name", type="string", length=255)
     */
    private $characterName;

    /**
     * @var string
     *
     * @ORM\Column(name="character_species", type="string", length=255)
     */
    private $characterSpecies;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text")
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="application")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
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
     * Set activityRate
     *
     * @param string $activityRate
     * @return Application
     */
    public function setActivityRate($activityRate)
    {
        $this->activityRate = $activityRate;
    
        return $this;
    }

    /**
     * Get activityRate
     *
     * @return string 
     */
    public function getActivityRate()
    {
        return $this->activityRate;
    }

    /**
     * Set academyShip
     *
     * @param string $academyShip
     * @return Application
     */
    public function setAcademyShip($academyShip)
    {
        $this->academyShip = $academyShip;
    
        return $this;
    }

    /**
     * Get academyShip
     *
     * @return string 
     */
    public function getAcademyShip()
    {
        return $this->academyShip;
    }

    /**
     * Set mentorRequest
     *
     * @param string $mentorRequest
     * @return Application
     */
    public function setMentorRequest($mentorRequest)
    {
        $this->mentorRequest = $mentorRequest;
    
        return $this;
    }

    /**
     * Get mentorRequest
     *
     * @return string 
     */
    public function getMentorRequest()
    {
        return $this->mentorRequest;
    }

    /**
     * Set positionFirst
     *
     * @param string $positionFirst
     * @return Application
     */
    public function setPositionFirst($positionFirst)
    {
        $this->positionFirst = $positionFirst;
    
        return $this;
    }

    /**
     * Get positionFirst
     *
     * @return string 
     */
    public function getPositionFirst()
    {
        return $this->positionFirst;
    }

    /**
     * Set positionSecond
     *
     * @param string $positionSecond
     * @return Application
     */
    public function setPositionSecond($positionSecond)
    {
        $this->positionSecond = $positionSecond;
    
        return $this;
    }

    /**
     * Get positionSecond
     *
     * @return string 
     */
    public function getPositionSecond()
    {
        return $this->positionSecond;
    }

    /**
     * Set positionThird
     *
     * @param string $positionThird
     * @return Application
     */
    public function setPositionThird($positionThird)
    {
        $this->positionThird = $positionThird;
    
        return $this;
    }

    /**
     * Get positionThird
     *
     * @return string 
     */
    public function getPositionThird()
    {
        return $this->positionThird;
    }

    /**
     * Set hearAbout
     *
     * @param string $hearAbout
     * @return Application
     */
    public function setHearAbout($hearAbout)
    {
        $this->hearAbout = $hearAbout;
    
        return $this;
    }

    /**
     * Get hearAbout
     *
     * @return string 
     */
    public function getHearAbout()
    {
        return $this->hearAbout;
    }

    /**
     * Set characterName
     *
     * @param string $characterName
     * @return Application
     */
    public function setCharacterName($characterName)
    {
        $this->characterName = $characterName;
    
        return $this;
    }

    /**
     * Get characterName
     *
     * @return string 
     */
    public function getCharacterName()
    {
        return $this->characterName;
    }

    /**
     * Set characterSpecies
     *
     * @param string $characterSpecies
     * @return Application
     */
    public function setCharacterSpecies($characterSpecies)
    {
        $this->characterSpecies = $characterSpecies;
    
        return $this;
    }

    /**
     * Get characterSpecies
     *
     * @return string 
     */
    public function getCharacterSpecies()
    {
        return $this->characterSpecies;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Application
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set user
     *
     * @param \Webb\UserBundle\Entity\User $user
     * @return Application
     */
    public function setUser($user = null)
    {
        $this->user = $user;
        $this->user = is_array($user) ? new ArrayCollection($user) : $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Webb\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
