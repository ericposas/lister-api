<?php

namespace PHPapp\Helpers;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../..");
$dotenv->load();

/**
 * Description of AuthConfig
 *
 * @author webdev00
 */
class AuthConfig {
    
    public static function getConfig()
    {
        return [
            'domain' => $_ENV["AUTH0_DOMAIN"],
            'client_id' => $_ENV["AUTH0_CLIENT_ID"],
            'client_secret' => $_ENV["AUTH0_CLIENT_SECRET"],
            'redirect_uri' => $_ENV["AUTH0_REDIRECT_URI"],
            'scope' => 'openid profile email',
            'persist_access_token' => true,
            'persist_id_token' => true
        ];
    }
    
}
