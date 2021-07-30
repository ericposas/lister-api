<?php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="PHPapp\ExtendedRepositories\ItemRepository")
 * @ORM\Table(name="items")
 */
class Item {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $icon;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $image;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $link;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $meta;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenericList", fetch="LAZY")
     * @ORM\JoinColumn(name="parentlist_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $parentlist;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of icon
     */ 
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @return  self
     */ 
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
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
    public function setImage(string $image)
    {
        $this->image = $image;
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
    public function setLink(string $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get the value of meta
     */ 
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set the value of meta
     *
     * @return  self
     */ 
    public function setMeta(string $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Get the value of parentlist
     */ 
    public function getParentlist()
    {
        return $this->parentlist;
    }

    /**
     * Set the value of parentlist
     *
     * @return  self
     */ 
    public function addParentlist($parentlist)
    {
        $parentlist->setItem($this);
        $this->parentlist = $parentlist;
        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
