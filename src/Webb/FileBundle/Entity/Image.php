<?php
// src/Webb/FileBundle/Entity/Image.php
namespace Webb\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* @ORM\Entity
* @ORM\HasLifecycleCallbacks
* @ORM\Table(name="images")
*/
class Image {

    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $folder;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    //Temporary Variables
    private $old_path;
    private $web_root;
    private $upload_dir;
    private $file_name;

    /**
     * @param mixed $web_root
     */
    public function setWebRoot($web_root)
    {
        $this->web_root = $web_root;
    }

    /**
     * @param mixed $upload_dir
     */
    public function setUploadDir($upload_dir)
    {
        $this->upload_dir = $upload_dir;
    }

    private function getAbsolutePath() {
        return null === $this->path ? null : $this->web_root.'/'.$this->getWebPath();
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->upload_dir.'/'.$this->folder.'/'.$this->id.'/'.$this->path;
    }

    private function getDir() {
        return $this->web_root.'/'.$this->upload_dir.'/'.$this->folder.'/'.$this->id;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->path =  preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $this->name))).'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->old_path)) {
            // delete the old image
            unlink($this->old_path);
            // clear the old image path
            $this->old_path = null;
        }

        $this->getFile()->move(
            $this->getDir(),
            $this->path
        );

        $this->setFile(null);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    private function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->old_path = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


}
