<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\User;
use PHPapp\Entity\Contact;
use PHPapp\AbstractResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\ORM\Query\Expr\Join;

class UserController extends AbstractResource
{
    public function index(Request $request, Response $response)
    {
        $repo = $this->getEntityManager()
                ->getRepository(User::class);
        
        $allUsers = $repo->getAllUsersIncludeContactInfo();
        
        if (isset($allUsers)) {
            return $response->withJson([
                "users" => $allUsers
            ]);
        } else {
            return $response->withJson([
                "message" => "Sorry, we couldn't get all users"
            ]);
        }
        
    }
    
    public function create(Request $request, Response $response)
    {
        $body = json_decode($request->getBody());
        
        if (isset($body->name))
        {
            $em = $this->getEntityManager();
            $repo = $em->getRepository(User::class);
            
            $user = $repo->createNewUser($body);
            
            if (isset($user)) {
                return $response->withJson([
                    "message" => "New user {$body->name} created."
                ]);
            } else {
                return $response->withJson([
                    "message" => "Could not create a new User."
                ]);
            }
        } else {
            return $response->withJson([
                "message" => "Could not create a new User. Send at least a name in request body."
            ]);
        }
        
    }
    
    public function show(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        
        if (isset($id)) {
            
            $user = $this->getEntityManager()
                    ->getRepository(User::class)
                    ->getSingleUser($id);
            
            if (isset($user) && $user != false) {
                return $response->withJson([
                    "user" => $user
                ]);
            }
            
        } else {
            return $response->withJson([ "message" => "No User found with an id of {$id}" ]);
        }
        return $response->withJson([
            "message" => "Could not get the User"
        ]);
    }
    
    public function delete(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        if (isset($id)) {
            $em = $this->getEntityManager();
            $user = $em->getRepository(User::class)
                    ->find($id);

            $emailRef = $user->getContact()->getEmail();
            
            if (isset($user)) {
                $contact = $user->getContact();
                $contact->addUser(null);
                $user->addContact(null);
                $em->flush();
                $em->remove($user);
                $em->remove($contact);
                $em->flush();

                return $response->withJson([
                    "message" => "Removed User {$user->getName()} at {$emailRef}"
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