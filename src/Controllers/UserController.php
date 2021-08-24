<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\User;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    protected $container = null; 
    protected $entityManager = null;
    
    public function __construct(\Psr\Container\ContainerInterface $container) {
        $this->container = $container;
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
    }

    public function index(Request $request, Response $response)
    {
        $repo = $this->entityManager
                ->getRepository(User::class);
        $users = $repo->findAll();
        
        if (empty($users)) {
            return $response->withJson([ "message" => "We couldn't find any Users" ]);
        }
        
        $data = $repo->getAllUsers($users);        
        return $response->withJson([
            "users" => $data
        ]);
    }
    
    public function create(Request $request, Response $response)
    {
        $body = json_decode($request->getBody());
        
        if (isset($body->name)) {
            $em = $this->entityManager;
            $repo = $em->getRepository(User::class);
            $user = $repo->createNewUser($body);
            
            if (isset($user)) {
                return $response->withJson([ "message" => "New user {$body->name} created." ]);
            } else {
                return $response->withJson([ "message" => "Could not create a new User." ]);
            }
        } else {
            return $response->withJson([ "message" => "Could not create a new User. Send at least a name in request body." ]);
        }
    }
    
    public function show(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        if (isset($id)) {
            $em = $this->entityManager;
            $repo = $em->getRepository(User::class);
            $user = $repo->getUser($id);
            
            return $response->withJson([ "user" => $user ]);
        } else {
            return $response->withJson([ "message" => "No User found with an id of {$id}" ]);
        }
        return $response->withJson([
            "message" => "Could not get the User"
        ]);
    }
    
    public function showLists(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        if (isset($id)) {
            $em = $this->entityManager;
            $user = $em->getRepository(User::class)
                    ->find($id);
            $listRepo = $this->entityManager
                ->getRepository(\PHPapp\Entity\GenericList::class);
            
            if (empty($user)) {
                return $response->withJson([
                    "message" => "User does not exist"
                ]);
            }
            $lists = $user->getLists();
            $listData = $listRepo->getListData($lists);
            if (empty($listData)) {
                return $response->withJson([
                    "message" => "User {$user->getName()} has no lists"
                ]);
            }
        }
        
        return $response->withJson([
            "lists" => $listData
        ]);
        
    }
    
    public function delete(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        if (isset($id)) {
            $em = $this->entityManager;
            $user = $em->getRepository(User::class)
                    ->find($id);

            $contact = $user->getContact();
            if (isset($contact)) {
                $emailRef = $user->getContact()->getEmail();
            }
            
            if (isset($user)) {
                $contact = $user->getContact();
                $user->addContact(null); # set any contact_id REFERENCE to null 
                $em->flush(); # flush that change to database to invalidate Foreign Key rel.
                # We removed contact_id REF, but since Contact still holds an FK through user_id
                # it will be cascade deleted when User is deleted
                $em->remove($user); # now we can delete the User and delete should cascade
                $em->flush();

                return $response->withJson([
                    "message" => isset($emailRef) ? "Removed User {$user->getName()} at {$emailRef}" : "{$user->getName()} User removed"
                ]);
            } else {
                return $response->withJson([
                    "message" => "No User with an id of {$id} exists"
                ]);
            }
        }
        return $response->withJson([
            "message" => "No User id parameter provided"
        ]);
    }
    
}