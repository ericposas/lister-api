<?php

namespace PHPapp\Controllers;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController extends \PHPapp\AbstractResource
{
    
    public function index(Request $request, Response $response)
    {
        $repo = $this->getEntityManager()->getRepository(\PHPapp\Entity\Contact::class);
        $data = $repo->getAllContacts();
        
        return $response->withJson([
            "data" => $data
        ]);
    }
    
    /**
     * @var id User id to get contact data for
     * @return JSON Response returns result of query for a single User's Contact info
     */
    public function show(Request $request, Response $response, array $urlParams) {
        
        if (isset($urlParams["id"]))
        {
            $repo = $this->getEntityManager()->getRepository(\PHPapp\Entity\Contact::class);
            $data = $repo->getSingleContactByUserId($urlParams["id"]);

            if (!empty($data))
            {
                return $response->withJson([
                    "contact" => $data // <-- return only first result since matching on "id"
                ], 201);
            } else {
                return $response->withJson([
                    "contact" => "Could not find contact info for that User"
                ], 400);    
            }
        } else {
            return $response->withJson([
                "contact" => "You must provide a User id to look up"
            ], 400);
        }
    }
    
}