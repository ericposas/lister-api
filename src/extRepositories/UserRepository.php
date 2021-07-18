<?php

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function setContactDetails($body)
    {
        // check if body data
        if (isset($body->email) && isset($body->phone)) {
            $contact = new Contact();
            $contact->setUser($user);
            $contact->setEmail($body->email);
            $contact->setPhone($body->phone);
            $em->persist($contact);    
            $data["email set"] = true;
            $data["phone set"] = true;
        }
        
        return $contact;
        
    }
    
}