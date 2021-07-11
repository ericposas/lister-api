<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/src'), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

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

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
