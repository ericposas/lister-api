<?php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="profiles")
 */
class Profile {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $thumb;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $link;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $image;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $shortDescription;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $longDescription;


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of thumb
     */ 
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Set the value of thumb
     *
     * @return  self
     */ 
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
        return $this;
    }

    /**
     * Get the value of link
     */ 
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set the value of link
     *
     * @return  self
     */ 
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get the value of shortDescription
     */ 
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set the value of shortDescription
     *
     * @return  self
     */ 
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * Get the value of longDescription
     */ 
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set the value of longDescription
     *
     * @return  self
     */ 
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
        return $this;
    }
}
