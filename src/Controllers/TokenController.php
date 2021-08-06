<?php

namespace PHPapp\Controllers;

use PHPapp\EntityManagerResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Auth0\SDK\Auth0;
        
/**
 * Description of TokenController
 *
 * @author webdev00
 */
class TokenController extends EntityManagerResource {

    public function delete (Request $request, Response $response, array $params)
    {
        # Place in a middleware function --
        $auth0 = new Auth0(\PHPapp\Helpers\AuthConfig::getConfig());
        $user = $auth0->getUser();
        
        if (! $user) {
            $response->getBody()->write("You need be logged in to perform this action");
            return $response;
        }
        # Place in a middleware function --
        
        $em = $this->getEntityManager();
        $whitelistedTokenRepo = $em->getRepository(\PHPapp\Entity\WhitelistedToken::class);
        $deletedTokenRepo = $em->getRepository(\PHPapp\Entity\DeletedToken::class);
        
        $tokenToDelete = $whitelistedTokenRepo->find($params["id"]);
        $em->remove($tokenToDelete);
        
        $tokenRecord = new \PHPapp\Entity\DeletedToken();
        $tokenRecord->setJWT($tokenToDelete->getJWT());
        $em->persist($tokenRecord);
        
        $em->flush();
        
        return $response->withRedirect("/my-tokens");
        
    }
    
    public function generate (Request $request, Response $response)
    {
        # Place in a middleware function --
        $auth0 = new Auth0(\PHPapp\Helpers\AuthConfig::getConfig());
        $user = $auth0->getUser();
        
        if (! $user) {
            $response->getBody()->write("You need be logged in to perform this action");
            return $response;
        }
        # Place in a middleware function --
        
        $em = $this->getEntityManager();
        
        $auth0->logout();
        $auth0->login();
    }
    
}
