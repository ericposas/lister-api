<?php

namespace PHPapp\ExtendedRepositories;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class ListRepository extends EntityRepository
{
    /**
     * @param int $id List id
     * @return User $user User() object
     */
    public function getUserByListId(int $id)
    {
        try {
            // look up User by List id
            $userQueryResult = $this
                    ->getEntityManager()
                    ->createQueryBuilder()
                    ->select("u", "l")
                    ->from("PHPapp\Entity\User", "u")
                    ->join("u.lists", "l")
                    ->where("l.id = :id")
                    ->setParameters([
                        "id" => $id
                    ])
                    ->getQuery()
                    ->getResult();

            foreach($userQueryResult as $i => $val)
            {
                $user = $userQueryResult[$i];
            }
            return $user;
        } catch (UniqueConstraintViolationException $ex) { // <-- This isn't working; need to look into catching Exceptions in Doctrine
            return $ex;
        }
    }
}