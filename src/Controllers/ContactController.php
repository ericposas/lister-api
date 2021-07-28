<?php

namespace PHPapp\Controllers;

use PHPapp\Entity\User;
use PHPapp\Entity\Contact;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController extends \PHPapp\EntityManagerResource
{
    
    public function create(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $body = json_decode($request->getBody());
        $em = $this->getEntityManager();
        $userRepo = $em->getRepository(User::class);
        
        $user = $userRepo->find($id);
        if (isset($id) && isset($body)) {
            if (empty($user)) {
                return $response->withJson([ "message" => "Couldn't find a User with id of {$id}" ], 404);
            }
            $createdOrChanged = "";
            $contactExist = $user->getContact();
            if (isset($contactExist)) {
                $createdOrChanged = "Changed";
                isset($body->email) ? $contactExist->setEmail($body->email) : null;
                isset($body->phone) ? $contactExist->setPhone($body->phone) : null;
            } else {
                $createdOrChanged = "Created new";
                $contact = new Contact();
                isset($body->email) ? $contact->setEmail($body->email) : null;
                isset($body->phone) ? $contact->setPhone($body->phone) : null;
                $em->persist($contact);
                $contact->addUser($user);
                $user->addContact($contact);
            }
            $em->flush(); # flush changes to db aka close the transaction
        } else {
            return $response->withJson([
                "message" => "Please provide a User {$id} and some body request data i.e. { 'email':'mymail@mail.com', 'phone': '000000000' }"
            ]);
        }
        
        return $response->withJson([
            "message" => "{$createdOrChanged} Contact info and attached to {$user->getName()}"
        ], 200);
        
    }
    
    public function delete(Request $request, Response $response, array $params)
    {
        $id = $params["id"];
        $em = $this->getEntityManager();
        $contact = $em->getRepository(Contact::class)
                ->find($id);
        if (isset($contact)) {
            $user = $contact->getUser();
            if (isset($user)) {
                $contact->addUser(null);
                $user->addContact(null);
                $em->flush();
            } else {
                return $response->write('boop');
            }
            $em->remove($contact);
            $em->flush();
            
            return $response->withJson([
                "message" => "Contact with id of {$id} was deleted"
            ]);
        }
        
        return $response->withJson([
            "message" => "Couldn't find a Contact with id {$id}"
        ]);
    }
    
}