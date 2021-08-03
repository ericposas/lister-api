<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);
        
	$userInfo = $auth0->getUser();

	if (!$userInfo) {
            $auth0->login();
	} else {
            
//            return $response->withRedirect("/");
            
//            $response->getBody()->write((string) json_encode([
//                "api_token" => $auth0->getIdToken()
//            ]));
//            return $response->withHeader("content-type", "application/json");
            
            $response->getBody()->write(""
                    . "<div>Your API token:</div>"
                    . "{$auth0->getIdToken()}"
                    . "<div></div>");
            return $response;
	}

    }
    
}
