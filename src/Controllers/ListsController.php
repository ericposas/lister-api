<?php
// src/Controllers/ListsController.php

namespace PHPapp\Controllers;

use PHPapp\Entity\Item;
use PHPapp\AbstractResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PHPapp\ExtendedRepositories\DuplicateItemException;
//use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ListsController extends AbstractResource
{
    /**
     * @action POST
     * @param int $id -- List id
     * @description Creates a new Item and adds it to a List
     */
    public function createItem(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->getEntityManager();
        $repo = $em->getRepository(\PHPapp\Entity\GenericList::class);
        $list = $repo->find($id);
        $user = $repo->getUserByListId($id);
        
        if (isset($list))
        {
            if (isset($body->name))
            {
                $itemCreateSucceeded = $repo->createItemIfUniqueInUserList($list, $body);
                if ((bool)$itemCreateSucceeded)
                {
                    return $response->withJson([
                        "message" => "A new Item was created and saved to {$user->getName()}'s List {$list->getName()}"
                    ]);
                } else
                {
                    return $response->withJson([
                        "message" => "Could not save a new Item"
                    ]);
                }

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