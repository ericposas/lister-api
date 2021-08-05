<?php

namespace PHPapp\Helpers;

$dotenvPath = __DIR__ . "/../..";
\Dotenv\Dotenv::createImmutable($dotenvPath)->load();
if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable($dotenvPath, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable($dotenvPath, ".env.stage")->load();
}

/**
 * Description of DBConnectionHelper
 *
 * @author webdev00
 */
class DBConnectionHelper {
    
    public static function getDBConnection ()
    {
        return array(
            'driver' => 'pdo_mysql',
            'host' => $_ENV["DB_HOST"],
            'port' => 3306,
            'dbname' => $_ENV["DB_NAME"],
            'user' => $_ENV["DB_ROOT_USER"],
            'password' => $_ENV["DB_ROOT_PASSWORD"],
            'charset' => 'utf8'
        );
    }
    
}