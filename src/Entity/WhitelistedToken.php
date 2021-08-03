<?php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="token_whitelist")
 */
class WhitelistedToken {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length="999", unique=true, nullable=false)
     */
    protected $jwt;
    
    /**
     * @ORM\ManyToOne(targetEntity="APIUser", fetch="LAZY")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $owner;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getJWT()
    {
        return $this->jwt;
    }
    
    public function setJWT($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
    
    public function addOwner($owner)
    {
        $this->owner = $owner;
        $owner->setToken($this);
        return $this;
    }
    
}
