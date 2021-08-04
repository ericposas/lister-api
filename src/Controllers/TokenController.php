<?php

namespace PHPapp\Controllers;

use PHPapp\EntityManagerResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of TokenController
 *
 * @author webdev00
 */
class TokenController extends EntityManagerResource {

    public function delete (Request $request, Response $response, array $params) {
        $em = $this->getEntityManager();
        $whitelistedTokenRepo = $em->getRepository(\PHPapp\Entity\WhitelistedToken::class);
        $deletedTokenRepo = $em->getRepository(\PHPapp\Entity\DeletedToken::class);
        
        $tokenToDelete = $whitelistedTokenRepo->find($params["id"]);
        $em->remove($tokenToDelete);
        
        $tokenRecord = new \PHPapp\Entity\DeletedToken();
        $tokenRecord->setJWT($tokenToDelete->getJWT());
        $em->persist($tokenRecord);
        
        $em->flush();
        
        return $response->withJson([
            "message" => "token {$params["id"]} was deleted."
        ]);
        
    }
    
}
