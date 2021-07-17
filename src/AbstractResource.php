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
$denv = \Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$denv->load();

abstract class AbstractResource
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

    // database configuration parameters
    $conn = array(
        'driver' => 'pdo_mysql',
        'host' => 'db',
        'port' => 3306,
        'dbname' => $_ENV['MYSQL_DB_NAME'],
        'user' => $_ENV['MYSQL_ROOT_USER'],
        'password' => $_ENV['MYSQL_ROOT_PASSWORD'],
        'charset' => 'utf8'
    );

    // return "why no .env vars? {$_ENV}";

    // obtaining the entity manager
    return EntityManager::create($conn, $config);

  }
}
