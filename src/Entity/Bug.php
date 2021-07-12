<?php
// src/Models/Bug.php

namespace PHPapp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="PHPapp\ExtendedRepositories\BugRepository")
 * @ORM\Table(name="bugs")
 */
class Bug
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   * @var int
   */
  protected $id;

  /**
   * @ORM\Column(type="string")
   * @var string
   */
  protected $description;

  /**
   * @ORM\Column(type="string")
   * @var DateTime
   */
  protected $created;

  /**
   * @ORM\Column(type="string")
   * @var string;
   */
  protected $status;

  /**
   * @ORM\ManyToOne(targetEntity="User", inversedBy="reportedBugs")
   */
  protected $reporter;

  /**
   * @ORM\ManyToOne(targetEntity="User", inversedBy="assignedBugs")
   */
  protected $engineer;

  /**
   * @ORM\ManyToMany(targetEntity="Product")
   */
  protected $products;

  public function __construct()
  {
    $this->products = new ArrayCollection();
  }

  public function getId()
  {
    return $this->id;
  }

  public function close()
  {
    $this->status = "CLOSE";
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setCreated(DateTime $created)
  {
    $this->created = $created->format('Y-m-d H:i:s');
  }

  public function getCreated()
  {
    return $this->created;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function setEngineer(User $engineer)
  {
    $engineer->assignedToBug($this);
    $this->engineer = $engineer;
  }

  public function getEngineer()
  {
    return $this->engineer;
  }
  
  public function setReporter(User $reporter)
  {
    $reporter->addReportedBug($this);
    $this->reporter = $reporter;
  }
  
  public function getReporter()
  {
    return $this->reporter;
  }

  public function assignToProduct(Product $product)
  {
    $this->products[] = $product;
  }

  public function getProducts()
  {
    return $this->products;
  }

}