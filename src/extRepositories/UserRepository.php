<?php
// src/models/BugRepository.php

namespace PHPapp\ExtendedRepositories;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUserContactInfo(int $id)
    {
      $em = $this->getEntityManager();
      $repo = $em->getRepository(\PHPapp\Entity\User::class);
      $user = $repo->find($id);
      $existingContactInfo = $user->getContact();
      return $existingContactInfo;
    }
    
    public function setUserContactInfo(int $id, $responseBody)
    {
        if (isset($body->email) && isset($body->phone))
        {
            $contact = $this->getUserContactInfo($id);
            $contact->setEmail($responseBody->email);
            $contact->setPhone($responseBody->phone);
            $this->getEntityManager()->flush();
            return true;
        } else {
            return false;
        }
    }
}
