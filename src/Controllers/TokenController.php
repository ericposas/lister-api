<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
        
/**
 * Description of TokenController
 *
 * @author webdev00
 */
class TokenController
{

    protected $container;
    protected $entityManager;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
    }
    
    public function delete (Request $request, Response $response, array $params)
    {
        # Place in a middleware function --
        $auth0 = new Auth0(\PHPapp\Helpers\AuthConfig::getConfig());
        $user = $auth0->getUser();
        
        if (! $user) {
            $response->getBody()->write("You need be logged in to perform this action");
            $response->withStatus(401);
            return $response;
        }
        # Place in a middleware function --
        
        $em = $this->entityManager;
        $whitelistedTokenRepo = $em->getRepository(\PHPapp\Entity\WhitelistedToken::class);
        
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
            $response->withStatus(401);
            return $response;
        }
        # Place in a middleware function -- 
        
        $auth0->logout();
        $auth0->login();
    }
    
}
