<?php
// src/Entity/User.php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class User
{
  /**
   * @OA\Property(type="integer", description="generated id of User entity")
   */
  protected $id;

  /**
   * @OA\Property(type="string", description="name of User entity")
   */
  protected $name;
  
  /**
   * @OA\Property(ref="#/components/schemas/Contact")
   */
  protected $contact;
  
  /**
   * @OA\Property(
   *    type="array",
   *    @OA\Items(ref="#/components/schemas/GenericList")
   * )
   */
  protected $lists;
  
}