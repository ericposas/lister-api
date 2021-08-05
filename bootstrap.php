<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

\Dotenv\Dotenv::createImmutable(__DIR__)->load();
if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.stage")->load();
}

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/src'), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

// database configuration parameters
$conn = \PHPapp\Helpers\DBConnectionHelper::getDBConnection();

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
