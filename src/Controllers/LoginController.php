<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get env vars
$denv = \Dotenv\Dotenv::createImmutable(__DIR__."/../..");
$denv->load();

class LoginController {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0 = new Auth0(\PHPapp\Helpers\AuthConfig::getConfig());
        
	$userInfo = $auth0->getUser();

	if (!$userInfo) {
            $auth0->login();
	} else {
//            return $response->withRedirect("/");
            $response->getBody()->write((string) json_encode([
                "api_token" => $auth0->getIdToken()
            ]));
            return $response->withHeader("content-type", "application/json");
	}

    }
    
}
