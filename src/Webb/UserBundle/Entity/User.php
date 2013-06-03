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
     * @Assert\MaxLength(limit="255", message="The first name is too long.", groups={"Registration", "Profile"})
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your surname.", groups={"Registration", "Profile"})
     * @Assert\MaxLength(limit="255", message="The surname is too long.", groups={"Registration", "Profile"})
     */
    protected $surname;

    /**
     * @ORM\OneToOne(targetEntity="Application",  cascade={"persist"})
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\UserBundle\Entity\Application")
     */
    protected $application;

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

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication(Application $application = null)
    {
        $this->application = $application;
    }

    public function __toString()
    {
        return "$this->first_name $this->surname";
    }
}