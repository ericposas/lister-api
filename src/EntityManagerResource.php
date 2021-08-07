<?php
/**
 * Thanks to: https://busypeoples.github.io/post/slim-doctrine/
 * Was able to get the EntityManager instance in my Controller 
 * by extending this super useful AbstractResource Class
 * 
 * This was key for combining Slim framework with Doctrine2,
 * and probably any other non-Symfony framework
 * 
 */

namespace PHPapp;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

// get env vars
$dotenvPath = __DIR__ . "/..";
\Dotenv\Dotenv::createImmutable($dotenvPath)->load();
if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable($dotenvPath, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable($dotenvPath, ".env.stage")->load();
}

abstract class EntityManagerResource
{
  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private $entityManager = null;

  /**
   * @return \Doctrine\ORM\EntityManager
   */
  public function getEntityManager()
  {
    if ($this->entityManager === null) {
      $this->entityManager = $this->createEntityManager();
    }
    return $this->entityManager;
  }
  
  /**
   * @return EntityManager
   */
  public function createEntityManager()
  {
    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;
    $useSimpleAnnotationReader = false;
    $config = Setup::createAnnotationMetadataConfiguration(array('Models'), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
    
    $conn = \PHPapp\Helpers\DBConnectionHelper::getDBConnection();

    // obtaining the entity manager
    return EntityManager::create($conn, $config);

  }
}
