<?php

namespace PHPapp\Helpers;

\Dotenv\Dotenv::createImmutable(__DIR__ . "/../..")->load();

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
            'host' => $_ENV["ENV"] == "local" ? $_ENV["LOCAL_DB_HOST"] : $_ENV["GOOGLE_DB_HOST"],
            'port' => 3306,
            'dbname' => $_ENV["ENV"] == "local" ? $_ENV["LOCAL_DB_NAME"] : $_ENV["GOOGLE_DB_NAME"],
            'user' => $_ENV["ENV"] == "local" ? $_ENV["LOCAL_DB_ROOT_USER"] : $_ENV["GOOGLE_DB_ROOT_USER"],
            'password' => $_ENV["ENV"] == "local" ? $_ENV["LOCAL_DB_ROOT_PASSWORD"] : $_ENV["GOOGLE_DB_ROOT_PASSWORD"],
            'charset' => 'utf8'
        );
    }
    
}