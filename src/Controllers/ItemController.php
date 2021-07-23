<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\Entity\GenericList;

use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ItemController extends \PHPapp\AbstractResource
{
    
    public function create(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->getEntityManager();
        $repo = $em->getRepository(GenericList::class);
        $itemRepo = $em->getRepository(Item::class);
        
        if (isset($id)) {
            $list = $repo->find($id);
            if (isset($list)) {
                if (isset($body->name)) {
                    $newItem = new Item();
                    $newItem->addParentlist($list);
                    $newItem = $itemRepo->dynamicSetAllItemProperties($newItem, $body);
                    $em->persist($newItem);
                    $em->flush();
                    return $response->withJson([
                        "message" => "Created a new Item and attached it to {$list->getName()}"
                    ], 201);
                } else {    
                    return $response->withJson([ "message" => "Need at least a name for the Item" ], 400);
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
        $em = $this->getEntityManager();
        $repo = $em->getRepository(Item::class);
        
        $item = $repo->find($id);
            
        if (empty($id)) {
            return $response->withJson([
                "message" => "Please provide the id of the Item you want to update"
            ]);
        }
        
        if (isset($body)) {
            if (empty($item)) {
                return $response->withJson([
                    "message" => "No Item exists with an id of {$id}"
                ]);
            } else {
                $item = $repo->dynamicSetAllItemProperties($item, $body);
            }
            $em->flush();
            
            return $response->withJson([
                "item" => "Item {$item->getName()} was updated with new properties"
            ], 201);
        }
        return $response->withJson([
            "message" => "You need to provide a request body with optional params: { name, icon, image, link, meta }"
        ], 400);
        
    }
    
}
