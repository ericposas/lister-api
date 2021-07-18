<?php
// src/models/BugRepository.php

namespace PHPapp\ExtendedRepositories;

use PHPapp\Entity\Contact;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param $id (User id)
     * @return array("user" => User, "contact" => Contact)
     */
    public function getUserContactInfo(int $id)
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
            (array)$contactUser = $this->getUserContactInfo($id);
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
