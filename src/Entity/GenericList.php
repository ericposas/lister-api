<?php

namespace PHPapp\Entity;

use PHPapp\Entity\Item;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
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
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", length=32, unique=true, nullable=false)
     */
    protected $id;
    
    /**
     * @Column(type="string", length=32, unique=false, nullable=false)
     */
    protected $name;
    
    /**
     * @OneToMany(targetEntity="Item", mappedBy="owning_list", cascade={"persist"}, orphanRemoval=true)
     * @var Item[]
     */
    protected $items;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"}, fetch="LAZY")
     */
    protected $owner;
    
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get the value of items
     *
     * @return  Item[]
     */ 
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @param  Item[]  $items
     *
     * @return  self
     */ 
    public function setItem(Item $item)
    {
        $item->setOwningList($this);
        $this->items[] = $item;
        return $this;
    }
}