<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\Entity\User;
use PHPapp\Entity\GenericList;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListsController
{
    protected \Psr\Container\ContainerInterface $container;
    protected \Doctrine\ORM\EntityManager $entityManager;

    public function __construct(\Psr\Container\ContainerInterface $container) {
        $this->container = $container;
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
    }
    
    public function index(Request $request, Response $response)
    {
        $listRepo = $this->entityManager->getRepository(GenericList::class);
        $lists = $listRepo->findAll();    
        $listData = $listRepo->getListData($lists);
        
        return $response->withJson([
            "lists" => $listData
        ], 200);
        
    }
    
    public function show(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $repo = $this->entityManager->getRepository(GenericList::class);
        
        if (isset($id)) {
            $list = $repo->find($id);
            if (isset($list)) {
                # iterate through items data
                $itemsData = array();
                $items = $list->getItems();
                if (isset($items)) {
                    $itemsData = $em->getRepository(Item::class)
                            ->getItemData($items);
                }
                return $response->withJson([
                    "data" => [
                        "owner" => $list->getOwner()->getName(),
                        "id" => $list->getId(),
                        "name" => $list->getName(),
                        "description" => $list->getDescription(),
                        "items" => $itemsData
                    ]
                ], 200);
            }
            return $response->withJson([
                "message" => "Could not find a List item with id {$id}"
            ], 404);
        }
        
        return $response->withJson([
            "message" => "Please provide a List id"
        ], 400);
        
    }
    
    public function create(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        
        if (empty($body->name)) {
            return $response->withJson([ "message" => "You need to provide at least a name for this List" ], 422);
        }
        $em = $this->entityManager;
        $userRepo = $em->getRepository(User::class);

        $addListResult = $userRepo->addNewList($id, $body);
        if ((bool)$addListResult["success"] !== true) {
            return $response->withJson([ "message" => "User with an id of {$id} does not exist." ], 404);
        }
        return $response->withJson([ "message" => "Added a new list for User {$addListResult["user"]->getName()}" ], 201);
    }
    
    public function update(Request $request, Response $response, array $params)
    {
        $body = json_decode($request->getBody());
        $repo = $this->entityManager->getRepository(GenericList::class);
        
        if (!$body) {
            return $response->withJson([ "message" => "You need to provide a request body" ]);
        }
        
        $status = $repo->updateList($params["id"], $body);
        if ($status["status"] === "success" && isset($status["user"])) {
            return $response->withJson([ "message" => "{$status["user"]->getName()} List was successfully updated" ]);
        }
        
        return $response->withJson([ "message" => "Could not update the user" ]);
    }
    
    public function delete(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $repo = $this->entityManager->getRepository(GenericList::class);
        $list = $repo->find($id);
        
        if (empty($id)) {
            return $response->withJson([ "message" => "Please provide the id of the List you want to delete" ], 422);
        }
        if (empty($list)) {
            return $response->withJson([ "message" => "Could not delete List with id {$id}, does not exist." ], 404);
        }
        $this->entityManager->remove($list);
        $this->entityManager->flush();
        
        return $response->withJson([ "message" => "Successfully deleted User {$list->getOwner()->getName()}'s List {$list->getName()}" ], 202);
    }
    
}