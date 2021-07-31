<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// get env vars
$denv = \Dotenv\Dotenv::createImmutable(__DIR__."/../..");
$denv->load();

class AuthCodeController {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0 = new Auth0([
          'domain' => $_ENV["AUTH0_DOMAIN"],
          'client_id' => $_ENV["AUTH0_CLIENT_ID"],
          'client_secret' => $_ENV["AUTH0_CLIENT_SECRET"],
          'redirect_uri' => $_ENV["AUTH0_REDIRECT_URI"],
          'scope' => 'openid profile email',
        ]);
        
        $userInfo = $auth0->getUser();
        $refreshToken = $auth0->getRefreshToken();
        
        if (isset($userInfo)) {
            $queryParams = $request->getQueryParams();
            $response->getBody()
                    ->write("<div>code: {$queryParams["code"]}</div>"
                    . "<div>state: {$queryParams["state"]}</div>"
                    . "<div>state: {$refreshToken}</div>");

            return $response;
        } else {
            $response->getBody()->write("No user, not logged in.");
            return $response;
        }
        
    }
    
}
