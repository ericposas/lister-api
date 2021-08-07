<?php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class GenericList
{
    /**
     * @var integer
     * @OA\Property
     */
    protected $id;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $owner;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $name;
    
    /**
     * @var string
     * @OA\Property
     */
    protected $description;
    
    /**
     * @var array
     * @OA\Property(
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/Item")
     * )
     */
    protected $items;

}