<?php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="shares")
 */
class Share {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\ManyToMany(targetEntity="GenericList", mappedBy="shares")
     */
    protected $lists;
    
    /**
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     */
    protected $senderId;
    
    /**
     * @ORM\Column(type="integer", length=32, unique=true, nullable=false)
     */
    protected $recipientId;

    public function __construct() {
        $this->lists = new ArrayCollection();
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getSenderId(): int
    {
        return $this->senderId;
    }
    
    public function setSenderId(int $id)
    {
        $this->senderId = $id;
    }
    
    public function getRecipientId(): int
    {
        return $this->recipientId;
    }
    
    public function setRecipientId(int $id)
    {
        $this->recipientId = $id;
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
    
}
