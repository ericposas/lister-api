<?php

namespace PHPapp\Middleware;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Description of AbstractMiddleware
 *
 * @author webdev00
 */
class CountMiddleware extends \PHPapp\AbstractResource {
    
    public function appendUserCount (Request $request, RequestHandler $handler) {
        
        $existingResponse = $handler->handle($request)->getBody();
        
        $em = $this->getEntityManager();
        $repo = $em->getRepository(\PHPapp\Entity\User::class);
        $users = $repo->findAll();
        
        $mwResponse = new \Slim\Psr7\Response();
        $userCount = (string)count($users);
        $ucString = "<div>You have added {$userCount} users.</div>";
        $mwResponse->getBody()->write("{$existingResponse} {$ucString}");
        
        return $mwResponse;
    }
    
}
