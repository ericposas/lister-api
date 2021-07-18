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

  public function index(Request $req, Response $res)
  {
    $em = $this->getEntityManager()->getRepository(\PHPapp\Entity\User::class);
    $users = $em->findAll();
    
    $data = array();
    foreach ($users as $idx => $user)
    {
        $data[$idx]["name"] = $user->getName();
        $contact = $user->getContact();
        if (isset($contact))
        {
            $data[$idx]["contact info"] = array(
                "email" => $user->getContact()->getEmail(),
                "phone" => $user->getContact()->getPhone()
            );
        } else
        {
            $data[$idx]["contact info"] = "not yet set";
        }
    }

    return $res->withJson([
      "message" => "retrieved list of all Users",
      "users" => $data
    ], 201);
  }
  
//  public function createList
  
  public function updateUserContactInfo(Request $request, Response $response, array $params)
  {
      $id = $params["id"];
      $body = json_decode($request->getBody());
      $repo = $this->getEntityManager()->getRepository(User::class);
      $setInfo = $repo->setUserContactInfo($id, $body);
              
      if ($setInfo === true)
      {
        return $response->withJson([
            "message" => "updated User {$id}",
            "data changed" => array([
                "email" => $body->email,
                "phone" => $body->phone
            ])
        ]);
      } else {
        return $response->withJson([
            "message" => "Could not update User {$id}",
            "data changed" => "none"
        ]);
      }
      
  }
    
  public function show(Request $req, Response $res, array $args)
  {
        if (isset($args["id"]))
        {
            $id = $args["id"];
            $repo = $this->getEntityManager()->getRepository(\PHPapp\Entity\User::class);
            $user = $repo->find($id);
            $contact = $user->getContact();
            
            if (isset($user))
            {
                if (isset($contact))
                {
                    return $res->withJson([
                      'message' => "Retrieved user with id: {$id}",
                      'user' => array(
                          "name" => $user->getName(),
                          "contact info" => array(
                              "email" => $user->getContact()->getEmail(),
                              "phone" => $user->getContact()->getPhone()
                          )
                       )
                    ], 201);
                } else {
                    return $res->withJson([
                      'message' => "Retrieved user with id: {$id}",
                      'user' => array(
                          "name" => $user->getName(),
                          "contact info" => "not set"
                       )
                    ], 201);
                }
            } else
            {
                return $res->withJson(array(
                    "message" => "Could not retrieve user with id: {$id}"
                ));
            }

        } else
        {
            return $res->withJson(array(
                "message" => "No id parameter provided in route"
            ));
        }
  }

  public function create(Request $req, Response $res)
  {
    $body = json_decode($req->getBody());
    $name = $body->name;
    
    $em = $this->getEntityManager();
    $newUser = new User();
    $newUser->setName($name);
    // if request body includes contact info
    if (isset($body->contact))
    {
        $contact = $body->contact;
        $newContact = new Contact();
        $newContact->setEmail($contact->email);
        $newContact->setPhone($contact->phone);
        $em->persist($newContact);
        $newUser->setContact($newContact);
    }
    $em->persist($newUser);
    $em->flush();

    return $res->withJson([
      'message' => "Created a new User {$contact->name}",
      'name' => $name,
      'email' => $contact->email,
      'phone' => $contact->phone,
    ], 201);
  }

}