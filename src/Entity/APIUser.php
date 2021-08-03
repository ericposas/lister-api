<?php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
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
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTokens($token)
    {
        return $this->tokens;
    }
    
    public function setToken($token)
    {
        $this->tokens[] = $token;
        return $this;
    }
    
    public function getNickname()
    {
        return $this->nickname;
    }
    
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getPicture()
    {
        return $this->picture;
    }
    
    public function setPicture($picture)
    {
        $this->picture = $picture;
        return $this;
    }
    
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function getEmailVerified()
    {
        return $this->email_verified;
    }
    
    public function setEmailVerified($email_verified)
    {
        $this->email_verified = $email_verified;
        return $this;
    }
    
}
