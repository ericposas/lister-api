<?php

namespace PHPapp\ExtendedRepositories;

use PHPapp\Entity\User;
use PHPapp\Entity\Contact;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    
    /**
     * @param Array $users takes an array of Users
     * @return Array returns an array of Users with contact info and Lists
     */
    public function getAllUsers(array $users)
    {
        $listRepo = $this->getEntityManager()
                ->getRepository(\PHPapp\Entity\GenericList::class);
        
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
        
        return $data;
    }
    
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
    
    /**
     * @param int $id takes in a User id int
     * @param requestBody $body takes in a decoded request body object
     * @return array returns an array with the $user and a bool success indicator
     */
    public function addNewList($id, $body)
    {
        try {
            $user = $this->getEntityManager()
                    ->getRepository(User::class)
                    ->find($id);
            $newList = new \PHPapp\Entity\GenericList();
            $newList->addOwner($user);
            $newList->setName($body->name);
            if (isset($body->description)) {
                $newList->setDescription($body->description);
            }
            $this->getEntityManager()->persist($newList);
            $this->getEntityManager()->flush();

            return [
                "user" => $user,
                "success" => true
            ];
        } catch (Exception $ex) {
            throw new Exception($ex);
        }
    }
    
    /**
     * @param object $body takes a request body
     * @return /PHPapp/Entity/User returns a single User entity
     */
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
            $em->persist($contact);
            $em->flush();
        }
        $em->persist($user);
        $em->flush();
        
        return $user;
    }
    
    public function getUser(int $id)
    {
        $user = $this->getEntityManager()
                ->getRepository(User::class)
                ->find($id);
        $data = [];
        $contactInfo = [];
        $contact = $user->getContact();
        $contactId = isset($contact) ? $contact->getId() : null;
        $email = isset($contact) ? $contact->getEmail() : null;
        $phone = isset($contact) ? $contact->getPhone() : null;
        if (isset($user)) {
            $data["id"] = $user->getId();
            $data["name"] = $user->getName();
        }
        $contactInfo["id"] = $contactId;
        $contactInfo["email"] = $email;
        $contactInfo["phone"] = $phone;
        $data["contactInfo"] = $contactInfo;
        
        # Need to include User's list data..
        
        
        return $data;
    }
    
    /**
     * @param int $id takes an id int and returns the corresponding User from db
     * @return Array returns an array with user info
     */
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