<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogoutController {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);
        
	$auth0->logout();      
//        $guzzle = new \GuzzleHttp\Client([
//            "base_uri" => "https://{$_ENV["AUTH0_DOMAIN"]}/v2/"
//        ]);
//        $returnTo = $_ENV["ENV"] == "local" ? $_ENV["LOCAL_AUTH0_LOGOUT_URI"] : $_ENV["AUTH0_LOGOUT_URI"];
//        $guzzleResponse = $guzzle->request("GET", "logout?returnTo={$returnTo}&client_id={$_SESSION["AUTH0_CLIENT_ID"]}");
//        
        return $response->withJson([
            "logout" => "done" //$guzzleResponse
        ]);

    }
    
}
