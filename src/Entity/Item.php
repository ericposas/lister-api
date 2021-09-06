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
    protected int $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=999, unique=false, nullable=true)
     */
    protected $icon;
    
    /**
     * @ORM\Column(type="string", length=999, unique=false, nullable=true)
     */
    protected $image;
    
    /**
     * @ORM\Column(type="string", length=999, unique=false, nullable=true)
     */
    protected $link;
    
    /**
     * @ORM\Column(type="string", length=999, unique=false, nullable=true)
     */
    protected $meta;
    
    /**
     * @ORM\Column(type="string", length=999, unique=false, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenericList", fetch="LAZY")
     * @ORM\JoinColumn(name="parentlist_id", referencedColumnName="id", onDelete="cascade")
     */
    protected GenericList $parentlist;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon(string $icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
        return $this;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta(string $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    public function getParentlist(): GenericList
    {
        return $this->parentlist;
    }

    public function addParentlist(GenericList $parentlist)
    {
        $parentlist->setItem($this);
        $this->parentlist = $parentlist;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }
}
