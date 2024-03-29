<?php

// src/Entity/Contact.php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="\PHPapp\ExtendedRepositories\ContactRepository")
 * @Table(name="contacts")
 */
class Contact {

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", length=32, nullable=true)
     */
    protected $id;
    
    /**
     * @Column(type="string", length=32, nullable=true)
     */
    protected $phone;
    
    /**
     * @Column(type="string", length=100, unique=true, nullable=true)
     */
    protected $email;
    
    /**
     * @OneToOne(targetEntity="User", cascade={"persist", "remove"})
     * @JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $user;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function addUser($user)
    {
        if (!empty($user)) {
            $user->addContact($this);
        }
        $this->user = $user;
        return $this;
    }
}
