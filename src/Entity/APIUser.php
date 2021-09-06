<?php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\PHPapp\ExtendedRepositories\APIUserRepository")
 * @ORM\Table(name="api_users")
 */
class APIUser {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="WhitelistedToken", mappedBy="owner", cascade={"persist"}, orphanRemoval=true)
     */
    protected $tokens;
    
    /**
     * @ORM\Column(type="string", length=99, unique=false, nullable=true)
     */
    protected $nickname;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length="999", unique=false, nullable=true)
     */
    protected $picture;
    
    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $email;
    
    /**
     * @ORM\Column(type="boolean", unique=false, nullable=true)
     */
    protected $email_verified;
    
    public function __construct()
    {
        $this->tokens = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getTokens()
    {
        return $this->tokens;
    }
    
    public function setToken(\PHPapp\Entity\WhitelistedToken $token)
    {
        $this->tokens[] = $token;
        return $this;
    }
    
    public function getNickname(): string
    {
        return $this->nickname;
    }
    
    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
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
    
    public function getPicture(): string
    {
        return $this->picture;
    }
    
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
        return $this;
    }
    
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
    
    public function setUpdatedAt(string $updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function getEmailVerified(): string
    {
        return $this->email_verified;
    }
    
    public function setEmailVerified(string $email_verified)
    {
        $this->email_verified = $email_verified;
        return $this;
    }
    
}
