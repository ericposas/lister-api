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
    protected int $id;
    
    /**
     * @ORM\Column(type="text", length="65535")
     */
    protected string $jwt;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getJWT(): string
    {
        return $this->jwt;
    }
    
    public function setJWT(string $jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
    
}
