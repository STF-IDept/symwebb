<?php
/**
 * src/Webb/UserBundle/Entity/User.php
 */

namespace Webb\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_users")
 */

class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your first name.", groups={"Registration", "Profile"})
     * @Assert\Length(max="255", maxMessage="The first name is too long.", groups={"Registration", "Profile"})
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your surname.", groups={"Registration", "Profile"})
     * @Assert\Length(max="255", maxMessage="The surname is too long.", groups={"Registration", "Profile"})
     */
    protected $surname;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Rank",  cascade={"persist"})
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Rank")
     */
    protected $rank;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var \DateTime $joined
     *
     * @ORM\Column(type="datetime")
     */
    protected $joined;

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
     * @ORM\OneToMany(targetEntity="Webb\CharacterBundle\Entity\Persona", mappedBy="user")
     */
    protected $persona;

    /**
     * @ORM\OneToOne(targetEntity="Application", cascade={"persist"}, mappedBy="user")
     */
    protected $application;

    /**
     * @ORM\OneToMany(targetEntity="Webb\PostBundle\Entity\History", mappedBy="user")
     */
    protected $history;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function __toString()
    {
        return "$this->first_name $this->surname";
    }

    /**
     * Set application
     *
     * @param \Webb\UserBundle\Entity\Application $application
     * @return User
     */
    public function setApplication($application = null)
    {
        if(is_array($application)) {
            $this->application = $application[0];
            return $this;
        }
        $this->application = $application;
        return $this;
    }

    /**
     * Get application
     *
     * @return \Webb\UserBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Add history
     *
     * @param \Webb\PostBundle\Entity\History $history
     * @return User
     */
    public function addHistory(\Webb\PostBundle\Entity\History $history)
    {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \Webb\PostBundle\Entity\History $history
     */
    public function removeHistory(\Webb\PostBundle\Entity\History $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return \DateTime
     */
    public function getJoined()
    {
        return $this->joined;
    }

    /**
     * @param \DateTime $joined
     */
    public function setJoined($joined)
    {
        $this->joined = $joined;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /*
     * @return string
     */

    public function getName() {
        return "$this->first_name $this->surname";
    }
}
