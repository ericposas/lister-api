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
//      $contactInfo = json_decode($request->getBody());
//      return $user = $em->find("PHPapp\Entity\User", $id);
    }
}
