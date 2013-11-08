<?php
/**
 * src/Webb/UserBundle/Entity/User.php
 */

namespace Webb\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToOne(targetEntity="Application", cascade={"persist"}, mappedBy="user")
     */
    protected $application;

    /**
     * @ORM\OneToMany(targetEntity="Webb\PostBundle\Entity\History", mappedBy="user")
     */
    private $history;

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
}
