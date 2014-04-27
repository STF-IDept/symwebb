<?php

namespace Webb\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Eko\FeedBundle\Item\Writer\ItemInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Note
 *
 * @ORM\Table(name="notes")
 * @ORM\Entity
 */

class Note implements ItemInterface
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
     * @ORM\ManyToOne(targetEntity="Location",  cascade={"persist"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\PostBundle\Entity\Location")
     * @Assert\NotBlank()
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="activity", type="string", length=255)
     */
    private $activity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Note",  cascade={"persist"}, inversedBy="child")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\PostBundle\Entity\Note")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\ShipBundle\Entity\Ship",  cascade={"persist"})
     * @ORM\JoinColumn(name="ship_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\ShipBundle\Entity\Ship")
     * @Assert\NotBlank()
     */
    private $ship;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\UserBundle\Entity\User",  cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\UserBundle\Entity\User")
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Persona",  cascade={"persist"})
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Persona")
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Webb\CharacterBundle\Entity\Assignment",  cascade={"persist"})
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id")
     * @Assert\Type(type="Webb\CharacterBundle\Entity\Assignment")
     * @Assert\NotBlank()
     */
    private $assignment;

    /**
     * @ORM\OneToMany(targetEntity="Webb\PostBundle\Entity\History", mappedBy="note")
     */
    private $history;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="Webb\PostBundle\Entity\Note", mappedBy="parent")
     */
    protected $child;

    /**
     * @ORM\OneToOne(targetEntity="Webb\PostBundle\Entity\Log", cascade={"persist"}, mappedBy="note")
     */
    protected $log;

    /**
     * @var integer
     *
     * @ORM\Column(name="thread", type="integer", nullable=true)
     */
    protected $thread;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer")
     */
    protected $published;

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
     * Set location
     *
     * @param string $location
     * @return Note
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set activity
     *
     * @param string $activity
     * @return Note
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    
        return $this;
    }

    /**
     * Get activity
     *
     * @return string 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set parent
     *
     * @param string $parent
     * @return Note
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return string 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set ship
     *
     * @param string $ship
     * @return Note
     */
    public function setShip($ship)
    {
        $this->ship = $ship;
    
        return $this;
    }

    /**
     * Get ship
     *
     * @return string 
     */
    public function getShip()
    {
        return $this->ship;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Note
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set persona
     *
     * @param string $persona
     * @return Note
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    
        return $this;
    }

    /**
     * Get persona
     *
     * @return string 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    public function getAssignment()
    {
        return $this->assignment;
    }

    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->child = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child
     *
     * @param \Webb\PostBundle\Entity\Note $child
     * @return Note
     */
    public function addChild(\Webb\PostBundle\Entity\Note $child)
    {
        $this->child[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Webb\PostBundle\Entity\Note $child
     */
    public function removeChild(\Webb\PostBundle\Entity\Note $child)
    {
        $this->child->removeElement($child);
    }

    /**
     * Get child
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Add history
     *
     * @param \Webb\PostBundle\Entity\History $history
     * @return Note
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
     * @return Log
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param string $log
     */
    public function setLog($log)
    {
        $log->setNote($this);
        $this->log = $log;

        return $this;
    }

    /**
     * @param integer $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
        return $this;
    }

    /**
     * @return integer
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param integer $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return integer
     */

    public function getPublished()
    {
        return $this->published;
    }

    public function getFeedItemTitle()
    {
        return "{$this->location} - {$this->activity}";
    }

    public function getFeedItemDescription()
    {
        return "{$this->assignment} played by {$this->user}<br/><br/> {$this->content}";
    }

    public function getFeedItemPubDate()
    {
        return $this->date;
    }

    public function getFeedItemLink()
    {
        //Use symfony class change needed
        $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return $url;
    }
}

