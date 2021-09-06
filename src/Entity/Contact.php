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
    protected int $id;
    
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
    protected User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function addUser(User $user)
    {
        if (!empty($user)) {
            $user->addContact($this);
        }
        $this->user = $user;
        return $this;
    }
}
