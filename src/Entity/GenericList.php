<?php
// src/Entity/GenericList.php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="PHPapp\ExtendedRepositories\ListRepository")
 * @Table(name="lists")
 */
class GenericList
{
    /**
     * @Id
     * @Column(type="integer", length=32, unique=true, nullable=false)
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $name;
    
    /**
     * @Column(type="string", length=999, unique=false, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parentlist", cascade={"persist"})
     */
    protected $items;

    /**
     * @ManyToOne(targetEntity="User", fetch="LAZY")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $owner;
    
    /**
     * @ORM\ManyToMany(targetEntity="Share", inversedBy="lists")
     * @ORM\JoinTable(
     *      name="shares_lists",
     *      joinColumns={@ORM\JoinColumn(name="share_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="list_id", referencedColumnName="id")}
     *  )
     */
    protected $lists;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of owner
     */ 
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */ 
    public function addOwner($owner)
    {
        $owner->setList($this);
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get the value of items
     */ 
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Set the value of the items array
     */
    public function setEmptyItems()
    {
        $this->items[] = [];
        return $this;
    }

    /**
     * Set the value of items
     *
     * @return  self
     */ 
    public function setItem($items)
    {
        $this->items[] = $items;
        return $this;
    }
}