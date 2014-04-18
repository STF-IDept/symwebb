<?php

namespace Webb\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity
 */
class History
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
     * @ORM\ManyToOne(targetEntity="Webb\UserBundle\Entity\User",  cascade={"persist"}, inversedBy="history")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\PostBundle\Entity\Note",  cascade={"persist"}, inversedBy="history")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\PostBundle\Entity\Note")
     */
    private $note;


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
     * Set user
     *
     * @param \Webb\UserBundle\Entity\User $user
     * @return History
     */
    public function setUser(\Webb\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

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

    /**
     * Set note
     *
     * @param \Webb\PostBundle\Entity\Note $note
     * @return History
     */
    public function setNote(\Webb\PostBundle\Entity\Note $note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return \Webb\PostBundle\Entity\Note 
     */
    public function getNote()
    {
        return $this->note;
    }
}
