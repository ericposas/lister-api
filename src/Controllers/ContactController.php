<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\Contact;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController
{
    protected $container;
    protected $entityManager;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
    }
    
    public function update(Request $request, Response $response, array $params) {
        return $this->create($request, $response, $params);
    }
    
    public function create(Request $request, Response $response, array $params)
    {
        $body = json_decode($request->getBody());
        $contactRepo = $this->entityManager->getRepository(Contact::class);
        
        if (isset($body)) {
            $updateResult = $contactRepo->createOrUpdateContact($params["id"], $body); # string returned
            if ($updateResult["status"] === "No user") {
                return $response->withJson([ "message" => "Couldn't find a User with id of {$params["id"]}" ], 404);
            }
        } else {
            return $response->withJson([
                "message" => "Please provide some body request data i.e. { 'email':'mymail@mail.com', 'phone': '000000000' }"
            ]);
        }
        return $response->withJson([
            "message" => "{$updateResult["status"]} Contact info and attached to {$updateResult["user"]->getName()}"
        ], 200);
    }
    
    public function delete(Request $request, Response $response, array $params)
    {
        $em = $this->entityManager;
        $contactRepo = $em->getRepository(\PHPapp\Entity\Contact::class);   
        $deleteOperationSucceeded = $contactRepo->deleteContact($params["id"]);
        if ($deleteOperationSucceeded === true) {
            return $response->withJson([
                "message" => "Contact with id of {$params["id"]} was deleted"
            ]);
        }
        return $response->withJson([
            "message" => "Couldn't find a Contact with id {$params["id"]}"
        ]);
    }
    
}