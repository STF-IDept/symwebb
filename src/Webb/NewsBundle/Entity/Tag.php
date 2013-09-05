<?php

namespace Webb\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Tag
 *
 * @ORM\Table(name="news_tags")
 * @ORM\Entity
 */
class Tag
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mach_name", type="string", length=255)
     */
    private $mach_name;

    /**
     * Inverse Side
     *
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     */
    private $articles;

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
     * @return Tag
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
     * To string method
     *
     * @return string
     */

    public function __toString()
    {
        return $this->name;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @return string
     */
    public function getMachName()
    {
        return $this->mach_name;
    }

    /**
     * @param string $mach_name
     */
    public function setMachName($mach_name)
    {
        $this->mach_name = $mach_name;

        return $this;
    }

}
