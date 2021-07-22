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
        $contacts = $this->getEntityManager()
                ->getRepository(Contact::class)
                ->findAll();
        
        foreach ($contacts as $c)
        {
            $data[]["user"] = [
                "name" => $c->getUser()->getName(),
                "email" => $c->getEmail(),
                "phone" => $c->getPhone(),
            ];
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
            if (isset($body->email))
            {
                $contact = new Contact();
                $contact->setEmail($body->email);
                $contact->setPhone($body->phone);
                $contact->setUser($user);
                $em->persist($contact);
            }
            $em->persist($user);
            $em->flush();

            return $response->withJson([
                "message" => "New user {$body->name} created."
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