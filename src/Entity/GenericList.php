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

    public function getId(): int
    {
        return $this->id;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function addOwner(User $owner)
    {
        $owner->setList($this);
        $this->owner = $owner;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }
    
    public function setEmptyItems()
    {
        $this->items[] = [];
        return $this;
    }

    public function setItem(Item $items)
    {
        $this->items[] = $items;
        return $this;
    }
}
