<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\Entity\User;
use PHPapp\EntityManagerResource;
use PHPapp\Entity\GenericList;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListsController extends EntityManagerResource
{
    
    public function index(Request $request, Response $response)
    {
        $em = $this->getEntityManager();
        $listRepo = $em->getRepository(GenericList::class);
        $lists = $listRepo->findAll();
        
        $listData = $listRepo->getListData($lists);
        
        return $response->withJson([
            "lists" => $listData
        ], 200);
        
    }
    
    public function show(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $em = $this->getEntityManager();
        $repo = $em->getRepository(GenericList::class);
        
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
        
        if (isset($id)) {
            if (isset($body->name)) {
                $em = $this->getEntityManager();
                $user = $em
                        ->getRepository(User::class)
                        ->find($id);
                
                if (isset($user)) {
                    $newList = new GenericList();
                    $newList->addOwner($user);
                    $newList->setName($body->name);
                    if (isset($body->description)) {
                        $newList->setDescription($body->description);
                    }
                    $em->persist($newList);
                    $em->flush();
                    
                } else {
                    return $response->withJson([
                        "message" => "User with an id of {$id} does not exist."
                    ], 404);
                }
                return $response->withJson([
                    "message" => "Added a new list for User {$user->getName()}"
                ], 201);
            } else {
                return $response->withJson([
                    "message" => "You need to provide at least a name for this List"
                ], 400);
            }
        }
        return $response->withJson([
            "message" => "Could not add a list to User object"
        ], 400);
    }
    
}