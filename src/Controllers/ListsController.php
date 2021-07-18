<?php
// src/Controllers/ListsController.php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\AbstractResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListsController extends AbstractResource
{
    /**
     * @param int $id -- List id
     */
    public function create(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->getEntityManager();
        $repo = $em->getRepository(\PHPapp\Entity\GenericList::class);
        $list = $repo->find($id);
        
        if (isset($list))
        {
            if (isset($body->name))
            {
                $item = new Item();
                $item->setName($body->name);
                if (isset($body->icon))
                {
                    $item->setIcon($body->icon);
                }
                if (isset($body->meta))
                {
                    $item->setMeta($body->meta);
                }
                $em->persist($item);
                $list->setItem($item);

                $em->flush();

                return $response->withJson([
                    "message" => "Added new Item {$body->name} to List {$list->getName()}"
                ]);
            } else
            {
                return $response->withJson([
                    "message" => "Please provide at least a name for the item in request body. Optional body params: icon (url to graphic), meta"
                ]);
            }
        } else {
            return $response->withJson([
                "message" => "Looks like that List object does not exist."
            ]);
        }
        
    }
}