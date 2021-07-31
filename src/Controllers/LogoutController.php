<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get env vars
$denv = \Dotenv\Dotenv::createImmutable(__DIR__."/../..");
$denv->load();

class LogoutController {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0 = new Auth0(\PHPapp\Helpers\AuthConfig::getConfig());
        
	$auth0->logout();
        
        return $response->withRedirect("/");

    }
    
}
