<?php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class Contact {

    /**
     * @var integer
     * @OA\Property(description="id of Contact entity")
     */
    protected $id;
    
    /**
     * @var string
     * @OA\Property(description="phone number")
     */
    protected $phone;
    
    /**
     * @var string
     * @OA\Property(description="email address")
     */
    protected $email;
    
    /**
     * @var integer
     * @OA\Property(property="user_id")
     */
    protected $user;
    
}
