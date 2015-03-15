<?php

namespace Webb\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Log
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity
 */
class Log
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
     * @ORM\OneToOne(targetEntity="Note", cascade={"persist"}, inversedBy="log")
     * @ORM\JoinColumn(name="note_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\PostBundle\Entity\Note")
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="log", type="boolean")
     */
    private $log;


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
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote(Note $note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return int
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param int $log
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

}
