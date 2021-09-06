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
   */
  protected $id;

  /**
   * @Column(type="string")
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

  public function getId(): int
  {
    return $this->id;
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

  public function getLists()
  {
    return $this->lists;
  }

  public function setList(GenericList $list)
  {
    $this->lists[] = $list;
    return $this;
  }

  public function getContact()  //: Contact -- currently returns null is no contact is set
  {
    return $this->contact;
  }

  public function addContact(Contact $contact)
  {
    $this->contact = $contact;
    return $this;
  }
}
