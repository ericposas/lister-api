<?php
// src/Entity/User.php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="PHPapp\ExtendedRepositories\UserRepository")
 * @Table(name="users")
 */
class User
{
  /**
   * @Id
   * @Column(type="integer", length=32, unique=true, nullable=false)
   * @GeneratedValue(strategy="IDENTITY")
   * @var int
   */
  protected $id;

  /**
   * @Column(type="string")
   * @var string
   */
  protected $name;
  
  /**
   * @OneToOne(targetEntity="Contact")
   * @JoinColumn(name="contact_id", referencedColumnName="id")
   */
  protected $contact;
  
  /**
   * @OneToMany(targetEntity="GenericList", mappedBy="owner", cascade={"persist"}, orphanRemoval=true)
   */
  protected $lists;

  public function __construct()
  {
      $this->lists = new ArrayCollection();
  }

  /**
   * Get the value of id
   * @return  int
   */ 
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get the value of name
   * @return  string
   */ 
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   * @param  string  $name
   * @return  self
   */ 
  public function setName(string $name)
  {
    $this->name = $name;
    return $this;
  }

  /**
   * Get arrayCollection of List objects
   *
   * @return  GenericList[]
   */ 
  public function getLists()
  {
    return $this->lists;
  }

  /**
   * Set arrayCollection of List objects
   *
   * @param  GenericList[]  $lists  ArrayCollection of List objects
   *
   * @return  self
   */ 
  public function setList($list)
  {
    $this->lists[] = $list;
    return $this;
  }

  /**
   * Get the value of contactInfo
   *
   * @return  string
   */ 
  public function getContact()
  {
    return $this->contact;
  }

  /**
   * Set the value of contactInfo
   *
   * @param  string  $contactInfo
   *
   * @return  self
   */ 
  public function addContact($contact)
  {
    $this->contact = $contact;
    return $this;
  }
}