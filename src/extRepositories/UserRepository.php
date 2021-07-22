<?php

namespace PHPapp\ExtendedRepositories;

use PHPapp\Entity\User;
use PHPapp\Entity\Contact;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getAllUsersIncludeContactInfo()
    {
        $contacts = $this
                ->getEntityManager()
                ->getConnection()
                ->fetchAllAssociative("select u.name, c.email, c.phone from users u left join contacts c on u.id = c.user_id");
        
        foreach($contacts as $c) {
            $data[] = $c;
        }
        
        return $data;
    }
    
    public function createNewUser($body)
    {
        $em = $this->getEntityManager();
        $user = new User();
        $user->setName($body->name);
        if (isset($body->email))
        {
            $contact = new Contact();
            $contact->setEmail($body->email);
            $contact->setPhone($body->phone);
            $contact->addUser($user);
//            $user->addContact($contact);
            $em->persist($contact);
            $em->flush();
        }
        $em->persist($user);
        $em->flush();
        
        return $user;
    }
    
    public function getSingleUser(int $id)
    {
        $results = $this
                ->getEntityManager()
                ->createQueryBuilder()
                ->select("u", "c")
                ->from("PHPapp\Entity\Contact", "c")
                ->join("c.user", "u")
                ->where("u.id = {$id}")
                ->getQuery()
                ->getArrayResult();
        
        if (isset($results[0]["user"]["name"])) {
            return [
                "user" => [
                    "name" => $results[0]["user"]["name"],
                    "email" => $results[0]["email"],
                    "phone" => $results[0]["phone"],
                ]
            ];
        } else {
            return (bool)false;
        }
    }
    
}