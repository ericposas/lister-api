<?php

# This Entity represents a token blacklist

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="deleted_tokens")
 */
class DeletedToken {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="text", length="65535")
     */
    protected $jwt;
    
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
    
}
