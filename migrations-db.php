<?php

use Doctrine\DBAL\DriverManager;

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable(__DIR__, ".env.stage")->load();
}

return DriverManager::getConnection([
  'dbname' => getenv("DB_NAME"),
  'user' => getenv("DB_ROOT_USER"),
  'password' => getenv("DB_ROOT_PASSWORD"),
  'host' => getenv("DB_HOST"),
  'driver' => 'pdo_mysql',
]);