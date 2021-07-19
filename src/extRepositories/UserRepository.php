<?php
// src/models/BugRepository.php

namespace PHPapp\ExtendedRepositories;

use PHPapp\Entity\Contact;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param $lists Users array of GenericList -- NULL or ARRAY
     * @return $listData string | array 
     */
    public function getUserListsData($lists, $user)
    {
        if (isset($lists) && count($lists) > 0)
        {   
            $listsData = array();
            foreach ($lists as $listIdx => $list)
            {
                $items = $list->getItems();
                $listItems = array();
                if (isset($items))
                {
                    foreach ($items as $idx => $item)
                    {
                        $listItems[] = array(
                            "name" => $item->getName(),
                            "meta" => $item->getMeta(),
                            "icon" => $item->getIcon(),
                            "image" => $item->getImage(),
                            "link" => $item->getLink(),
                        );
                    }
                }
                $listsData[]["list"] = array(
                    "name" => $list->getName(),
                    "items" => $listItems
                );
            }
        } else
        {
            $listsData[]["list"] = "User {$user->getName()} has no lists.";
        }
        return $listsData;
    }
    
    public function getUserContactData($contact, $user)
    {
        if (isset($contact))
        {
            $contactData = array(
                "email" => $contact->getEmail(),
                "phone" => $contact->getPhone()
            );
        } else
        {
            $contactData = "not yet set by User {$user->getName()}";
        }
        return $contactData;
    }


    /**
     * @param $id (User id)
     * @return array("user" => User, "contact" => Contact)
     */
    // Local method setUserContactInfo() uses this method
    public function getUserContact(int $id)
    {
      $em = $this->getEntityManager();
      $repo = $em->getRepository(\PHPapp\Entity\User::class);
      $user = $repo->find($id);
      $existingContactInfo = $user->getContact();
      if (isset($existingContactInfo))
      {
        // if existing return existing info
        return array("contact" => $existingContactInfo, "user" => $user);  
      } else
      {
        // else create new info
        $newContact = new Contact();
        return array("contact" => $newContact, "user" => $user);
      }
    }
    
    /**
     * @param $id (User id)
     * @param $responseBody { email, phone }
     * @return $success boolean
     */
    public function setUserContactInfo(int $id, $responseBody)
    {
        if (isset($responseBody->email) && isset($responseBody->phone))
        {
            // array contains instance of User and Contact returned from the getUserContactInfo method
            (array)$contactUser = $this->getUserContact($id);
            $contact = $contactUser["contact"];
            $user = $contactUser["user"];
            $contact->setEmail($responseBody->email);
            $contact->setPhone($responseBody->phone);
            $user->setContact($contact);
            $this->getEntityManager()->persist($contact);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            return true;
        } else {
            return false;
        }
    }
}
