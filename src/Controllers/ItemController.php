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
                    $itemRepo->dynamicSetAllItemProperties($newItem, $body);
                    
                    $em->persist($newItem);
                    $em->flush();

                    return $response->withJson([
                        "message" => "Created a new Item and attached it to {$list->getName()}"
                    ]);    

                } else {    
                    return $response->withJson([ "message" => "Need at least a name for the Item" ]);    
                }

            } else {
                return $response->withJson([ "message" => "No List with that {id} found." ]);
            }
        } else {
            return $response->withJson([ "message" => "Please provide a List {id}" ]);
        }
    }
}
