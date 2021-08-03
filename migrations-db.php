<?php

use Doctrine\DBAL\DriverManager;

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

return DriverManager::getConnection([
  'dbname' => getenv("ENV") == "local" ? getenv("LOCAL_DB_NAME") : getenv("GOOGLE_DB_NAME"),
  'user' => getenv("ENV") == "local" ? getenv("LOCAL_DB_ROOT_USER") : getenv("GOOGLE_DB_ROOT_USER"),
  'password' => getenv("ENV") == "local" ? getenv("LOCAL_DB_ROOT_PASSWORD") : getenv("GOOGLE_DB_ROOT_PASSWORD"),
  'host' => getenv("ENV") == "local" ? getenv("LOCAL_DB_HOST") : getenv("GOOGLE_DB_HOST"),
  'driver' => 'pdo_mysql',
]);