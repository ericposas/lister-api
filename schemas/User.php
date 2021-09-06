<?php
// src/Entity/User.php

namespace PHPapp\Schemas;

/**
 * @OA\Schema
 */
class User
{
  /**
   * @OA\Property(type="integer")
   */
  protected $id;

  /**
   * @OA\Property(type="string")
   */
  protected $name;
  
  /**
   * @OA\Property(property="contactInfo", ref="#/components/schemas/Contact")
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