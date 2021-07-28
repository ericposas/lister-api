<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\User;
use PHPapp\EntityManagerResource;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends EntityManagerResource
{
    
    public function index(Request $request, Response $response)
    {
        $users = $this->getEntityManager()
                ->getRepository(User::class)
                ->findAll();
        $listRepo = $this->getEntityManager()
                ->getRepository(\PHPapp\Entity\GenericList::class);
        
        if (empty($users)) {
            return $response->withJson([ "message" => "We couldn't find any Users" ]);
        }
        
        $data = array();
        foreach ($users as $user) {
            $contact = $user->getContact();
            $contactInfo = [
                "id" => isset($contact) ? $contact->getId() : null,
                "email" => isset($contact) ? $contact->getEmail() : null,
                "phone" => isset($contact) ? $contact->getPhone() : null,
            ];
            $lists = $user->getLists();
            $listData = $listRepo->getListData($lists);
            
            $data[] = [
                "id" => $user->getId(),
                "name" => $user->getName(),
                "contactInfo" => $contactInfo,
                "lists" => $listData
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
                    ->find($id);
            $data = [];
            $contact = $user->getContact();
            $email = isset($contact) ? $contact->getEmail() : null;
            $phone = isset($contact) ? $contact->getPhone() : null;
            isset($user) ? $data["name"] = $user->getName() : null;
            isset($email) ? $data["email"] = $email : null;
            isset($phone) ? $data["phone"] = $phone : null;
            
            return $response->withJson([ "user" => $data ]);
            
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
            $em = $this->getEntityManager();
            $user = $em->getRepository(User::class)
                    ->find($id);
            $listRepo = $this->getEntityManager()
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
            $em = $this->getEntityManager();
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