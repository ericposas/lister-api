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
    $em = $this->getEntityManager();
    $repo = $em->getRepository(User::class);
//    $qb = $this->getEntityManager()->createQueryBuilder();
//    $qb->select('u')
//            ->from("User", "u")
//            ->getQuery()
//            ->getResult();
    $users = $repo->findAll();
    
//    $em = $this->getEntityManager();
//    $repo = $em->getRepository(User::class);
//    $users = $repo->findAll();
//    $usersArr = array();
      
//      $qb = $this->getEntityManager()->createQueryBuilder();
//      $users = $qb->select("u")
//              ->from("PHPapp\Entity\User", "u")
//              ->join("PHPapp\Entity\Contact", "c")
//              ->getQuery()
//              ->getResult();
    
    foreach ($users as $key => $user)
    {
        $contact = $user->getContact();
        $usersArr[$key] = array([
            'name' => $user->getName(),
            'contact info' => array([
                'email' => $contact->getEmail(),
                'phone' => $contact->getPhone()
            ])
        ]);
    }

    return $res->withJson([
      'message' => 'a JSON response from the :get method',
      'users' => $usersArr
    ], 201);
  }
  
  public function getSingleUser(Request $req, Response $res, array $args)
  {
    return $res->withJson([
      'message' => 'a JSON response from the :get method',
      'params' => $args
    ], 201);
  }

  public function createUser(Request $req, Response $res, array $args)
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