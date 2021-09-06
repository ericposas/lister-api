<?php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class Contact {

    /**
     * @var integer
     * @OA\Property
     */
    protected $id;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $phone;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $email;
    
}
