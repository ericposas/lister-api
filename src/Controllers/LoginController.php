<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of LoginController
 *
 * @author webdev00
 */
class LoginController extends \PHPapp\EntityManagerResource {
    
    public function __invoke() {
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);
        $auth0->login(); # redirects us to / 
    }
    
}
