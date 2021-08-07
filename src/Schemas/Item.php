<?php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class Item {
    
    /**
     * @var integer
     * @OA\Property
     */
    protected $id;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $name;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $icon;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $image;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $link;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $meta;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $description;
    
    /**
     * @var int
     * @OA\Property(property="parentlist_id")
     */
    protected $parentlist;

}
