<?php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class GenericList
{
    /**
     * @var integer
     * @OA\Property(description="generated id")
     */
    protected $id;
    
    /**
     * @var string
     * @OA\Property(description="List name")
     */
    protected $name;
    
    /**
     * @var string
     * @OA\Property()
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

    /**
     * @var integer
     * @OA\Property(property="owner_id")
     */
    protected $owner;

}