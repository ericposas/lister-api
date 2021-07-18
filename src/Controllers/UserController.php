<?php
// src/Controllers/UserController.php

namespace PHPapp\Controllers;

use PHPapp\Entity\User;
use PHPapp\Entity\Contact;
use PHPapp\AbstractResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends AbstractResource
{
    public function index(Request $request, Response $response)
    {
        $results = $this->getEntityManager()->createQueryBuilder()
                ->select("u", "c")
                ->from("PHPapp\Entity\Contact", "c")
                ->join("c.user", "u")
                ->getQuery()
                ->getArrayResult();
        
        // return users with more info
        $data = array();
        foreach ($results as $idx => $result)
        {
            // flatten our data into simple json object 
            $data[$idx]["name"] = $result["user"]["name"];
            $data[$idx]["email"] = $result["email"];
            $data[$idx]["phone"] = $result["phone"];
        }
        
        return $response->withJson([
            "users" => $data
        ]);
    }
    
    public function create(Request $request, Response $response)
    {
        $body = json_decode($request->getBody());
        if (isset($body->name))
        {
            $em = $this->getEntityManager();
            $repo = $em->getRepository(\PHPapp\Entity\User::class);
            $user = new User();
            $user->setName($body->name);
            $em->persist($user);
            
            $data = array();
            $data["name set"] = true;

            $em->flush();

            return $response->withJson([
                "message" => "New user created.",
                "info" => $data
            ]);
        } else {
            return $response->withJson([
                "message" => "Could not create a new User."
            ]);
        }
        
    }
    
    public function show(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $results = $this->getEntityManager()->createQueryBuilder()
                ->select("u", "c")
                ->from("PHPapp\Entity\Contact", "c")
                ->join("c.user", "u")
                ->where("u.id = {$id}")
                ->getQuery()
                ->getArrayResult();
        
        return $response->withJson([
            "user" => array(
                "name" => $results[0]["user"]["name"],
                "email" => $results[0]["email"],
                "phone" => $results[0]["phone"],
            )
        ]);
    }
    
}