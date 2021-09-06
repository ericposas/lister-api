<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\Entity\GenericList;

use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ItemController
{
    protected $container;
    protected $entityManager;

    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
    }
    
    public function create(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->entityManager;
        $repo = $em->getRepository(GenericList::class);
        $itemRepo = $em->getRepository(Item::class);
        
        if (isset($id)) {
            $list = $repo->find($id);
            if (isset($list)) {
                if (isset($body->name)) {
                    $itemInstance = new Item();
                    $itemInstance->addParentlist($list);
                    $newItem = $itemRepo->dynamicSetAllItemProperties($itemInstance, $body);
                    $em->persist($newItem);
                    $em->flush();
                    return $response->withJson([
                        "message" => "Created a new Item and attached it to {$list->getName()}"
                    ], 201);
                } else {    
                    return $response->withJson([ "message" => "Need at least a name for the Item" ], 422);
                }
            } else {
                return $response->withJson([ "message" => "No List with that {id} found." ], 404);
            }
        } else {
            return $response->withJson([ "message" => "Please provide a List {id}" ], 400);
        }
    }
    
    public function update(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->entityManager;
        $repo = $em->getRepository(Item::class);
        $item = $repo->find($id);
        
        if (empty($id)) {
            return $response->withJson([
                "message" => "Please provide the id of the Item you want to update"
            ], 422);
        }
        
        if (isset($body)) {
            if (empty($item)) {
                return $response->withJson([ "message" => "No Item exists with an id of {$id}" ], 404);
            } else {
                $item = $repo->dynamicSetAllItemProperties($item, $body);
            }
            $em->flush();
            
            return $response->withJson([ "item" => "Item {$item->getName()} was updated with new properties" ], 201);
        }
        return $response->withJson([
            "message" => "You need to provide a request body with optional params: { name, icon, image, link, meta }"
        ], 400);
    }
    
    public function delete(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $em = $this->entityManager;
        $repo = $em->getRepository(Item::class);
        
        $item = $repo->find($id);
        
        if (empty($id)) {
            return $response->withJson([
                "message" => "Please provide the id of the Item you want to delete"
            ]);
        }
        
        $em->remove($item);
        $em->flush();
        
        return $response->withJson([
            "message" => "Item {$item->getName()} was successfully deleted from {$item->getParentlist()->getOwner()->getName()}'s List {$item->getParentlist()->getName()}"
        ]);
        
    }
    
}
