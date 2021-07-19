<?php

namespace PHPapp\Controllers;

use Slim\Http\Response;
use PHPapp\Entity\Item;
use PHPapp\AbstractResource;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of ItemController
 *
 * @author webdev00
 */
class ItemController extends AbstractResource {
    
    /**
     * @action GET Item
     * @var $id Item id
     * @return JSON { string "message", Item "item" }
     */
    public function show(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $em = $this->getEntityManager();
        $repo = $em->getRepository(\PHPapp\Entity\Item::class);
        $item = $repo->find($id);
        
        if (isset($item))
        {
            $itemData = array(
                "name" => $item->getName(),
                "meta" => $item->getMeta(),
                "icon" => $item->getIcon(),
                "image" => $item->getImage(),
                "link" => $item->getLink()
            );
        }
        
        return $response->withJson([
            "message" => "Retrieved item with id {$id}",
            "item" => $itemData
        ]);
        
    }
    
    /**
     * @action PUT Item
     * @var $id Item id
     * @param { JSON "meta", string "icon", string "name" } $body -- BODY PARAMS
     * @param int $id -- URL PARAM
     * @return JSON { string "message" }
     */
    public function update(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->getEntityManager();
        $repo = $em->getRepository(\PHPapp\Entity\Item::class);
        $item = $repo->find($id);
        
        if (isset($item))
        {
            if (isset($body->name))
            {
                $item->setName($body->name);
            }
            if (isset($body->meta))
            {
                $item->setMeta($body->meta);
            }
            if (isset($body->icon))
            {
                $item->setIcon($body->icon);            
            }
            if (isset($body->image))
            {
                $item->setImage($body->image);            
            }
            if (isset($body->link))
            {
                $item->setLink($body->link);            
            }
        }
        
//        $item->getOwningList()->setItem($item);
        $em->flush();
        
        return $response->withJson([
            "message" => "Updated Item with id {$id}"
        ]);
        
    }
    
}
