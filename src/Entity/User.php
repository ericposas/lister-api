<?php
// src/Entity/User.php

namespace PHPapp\Entity;

use PHPapp\Entity\Share;
use PHPapp\Entity\Contact;
use Doctrine\ORM\Mapping\Id;
use PHPapp\Entity\GroceryList;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
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
   * @OneToOne(targetEntity="Contact", mappedBy="user")
   * @JoinColumn(name="contact_id", referencedColumnName="id")
   */
  protected $contact;

  public function __construct()
  {
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
   * Get the value of contact
   */ 
  public function getContact()
  {
    return $this->contact;
  }

  /**
   * Set the value of contact
   *
   * @return  self
   */ 
  public function setContact($contact)
  {
    $this->contact = $contact;
    return $this;
  }
}