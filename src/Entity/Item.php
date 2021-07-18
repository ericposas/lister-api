<?php

namespace PHPapp\Entity;

use PHPapp\Entity\GenericList;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="items")
 */
class Item
{
    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", length=32, unique=true, nullable=false)
     */
    protected $id;
    
    /**
     * @Column(type="string", length=32, unique=true, nullable=false)
     */
    protected $name;
    
    /**
     * @Column(type="string", length=32, unique=true, nullable=true)
     * @var URL to resource
     */
    protected $icon;

    /**
     * @Column(type="string", length=32, unique=true, nullable=true)
     * @var METADATA -- Any data stored as JSON string
     */
    protected $meta;

    /**
     * @ManyToOne(targetEntity="GenericList", cascade={"all"}, fetch="LAZY")
     */
    protected $owning_list;
    

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
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get to resource
     *
     * @return  URL
     */ 
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set to resource
     *
     * @param  URL  $icon  to resource
     *
     * @return  self
     */ 
    public function setIcon(URL $icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Get -- Any data stored as JSON string
     *
     * @return  METADATA
     */ 
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set -- Any data stored as JSON string
     *
     * @param  METADATA  $meta  -- Any data stored as JSON string
     *
     * @return  self
     */ 
    public function setMeta(METADATA $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Get the value of owning_list
     */ 
    public function getOwningList()
    {
        return $this->owning_list;
    }

    /**
     * Set the value of owning_list
     *
     * @return  self
     */ 
    public function setOwningList(GenericList $owning_list)
    {
        $this->owning_list = $owning_list;
        return $this;
    }
}