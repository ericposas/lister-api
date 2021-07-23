<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\Entity\User;
use PHPapp\AbstractResource;
use PHPapp\Entity\GenericList;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListsController extends AbstractResource
{
    
    public function index(Request $request, Response $response)
    {
        $em = $this->getEntityManager();
        $listRepo = $em->getRepository(GenericList::class);
        $lists = $listRepo->findAll();
        
        $listData = array();
        foreach ($lists as $list) {
            $items = $list->getItems();
            $itemData = array();
            foreach ($items as $item) {
                foreach (\PHPapp\ExtendedRepositories\ItemRepository::$props as $prop) {
                    $bprop = strtolower($prop);
                    $getProp = "get{$prop}";
                    $properties["{$bprop}"] = $item->{$getProp}();
                }
                $itemData[]["item"] = $properties;
            }

            $listData[] = [
                "id" => $list->getId(),
                "owner" => $list->getOwner()->getName(),
                "name" => $list->getName(),
                "description" => $list->getDescription(),
                "items" => $itemData
            ];
        }
        
        return $response->withJson([
            "data" => $listData
        ]);
        
    }
    
    public function show(Request $request, Response $response, array $params)
    {
        # TODO
        return $response->withJson([
            "message" => "TODO: ListsController -- show()"
        ]);
    }
    
    public function create(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        
        if (isset($id))
        {
            
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
                    ]);
                }

                return $response->withJson([
                    "message" => "Added a new list for User {$user->getName()}"
                ]);
                
            } else {
                
                return $response->withJson([
                    "message" => "You need to provide at least a name for this List"
                ]);
                    
            }
            
        }
        
        return $response->withJson([
            "message" => "Could not add a list to User object"
        ]);
    }
    
}