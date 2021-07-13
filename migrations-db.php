<?php

use Doctrine\DBAL\DriverManager;

$dot = Dotenv\Dotenv::createImmutable(__DIR__);
$dot->load();

return DriverManager::getConnection([
  'dbname' => getenv('MYSQL_DB_NAME'),
  'user' => getenv('MYSQL_ROOT_USER'),
  'password' => getenv('MYSQL_ROOT_PASSWORD'),
  'host' => 'localhost',
  'driver' => 'pdo_mysql',
]);

// return [
//   'dbname' => 'db',
//   'user' => 'root',
//   'password' => '',
//   'host' => 'localhost',
//   'driver' => 'pdo_mysql',
// ];
