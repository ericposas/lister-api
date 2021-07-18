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

  public function getUsers(Request $req, Response $res)
  {
    $qb = $this->getEntityManager()->createQueryBuilder();
    $users = $qb->select("u", "c")
            ->from("PHPapp\Entity\User", "u")
            ->join("u.contact", "c")
            ->orderBy("u.id", "ASC")
            ->getQuery()
            ->getArrayResult();
    
    foreach ($users as $key => $user)
    {
        $usersArr[$key] = array([
            'name' => $user["name"],
            'contact info' => array([
                'email' => $user["contact"]["email"],
                'phone' => $user["contact"]["phone"]
            ])
        ]);
    }

    return $res->withJson([
      "message" => "retrieved list of all Users",
      "users" => $usersArr
    ], 201);
  }
  
  public function updateUserContactInfo(Request $request, Response $response, array $params)
  {
      $em = $this->getEntityManager();
      $id = $params["id"];
      $body = json_decode($request->getBody());
      $repo = $this->getEntityManager()->getRepository(User::class);
      $contact = $repo->getUserContactInfo($id);
              
      if (isset($body->email) && isset($body->phone))
      {
        $contact->setEmail($body->email);
        $contact->setPhone($body->phone);
        $em->flush();
        return $response->withJson([
            "message" => "updated User {$params->id}",
            "data changed" => array([
                "email" => $body->email,
                "phone" => $body->phone
            ])
        ]);
      } else {
        return $response->withJson([
            "message" => "Could not update User {$params->id}",
            "data changed" => "none"
        ]);
      }
      
  }
    
  public function getSingleUser(Request $req, Response $res, array $args)
  {
    return $res->withJson([
      'message' => 'a JSON response from the :get method',
      'params' => $args
    ], 201);
  }

  public function createUser(Request $req, Response $res)
  {
    $body = json_decode($req->getBody());
    $name = $body->name;
    $contact = $body->contact;
    
    $em = $this->getEntityManager();
    $newUser = new User();
    $newContact = new Contact();
    $newContact->setEmail($contact->email);
    $newContact->setPhone($contact->phone);
//    $newContact->setUser($newUser);
    $em->persist($newContact);
    $newUser->setName($name);
    $newUser->setContact($newContact);
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