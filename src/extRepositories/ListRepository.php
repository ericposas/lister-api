<?php

namespace PHPapp\ExtendedRepositories;

use PHPapp\Entity\Item;
use Doctrine\ORM\EntityRepository;
//use Exception;

//class DuplicateItemException extends Exception {
//    public function __construct(string $message = "", int $code = 0, \Throwable $previous = NULL): Exception {
//        parent::__construct($message, $code, $previous);
//    }
//}

class ListRepository extends EntityRepository
{
    
    public function createItemIfUniqueInUserList($list, $body)
    {
        $em = $this->getEntityManager();
        $items = $list->getItems();
        if (isset($items))
        {
            foreach ($items as $_item)
            {
                $itemsData[] = $_item->getName();
            }
            foreach ($itemsData as $key => $val)
            {
                if ($val === $body->name) // check if any existing items in list match the body param "name"
                {
                    return false;
                }
            }
        }

        $item = new Item();
        if (isset($body->name))
        {
            $item->setName($body->name);
        }
        if (isset($body->icon))
        {
            $item->setIcon($body->icon);
        }
        if (isset($body->image))
        {
            $item->setImage($body->image);
        }
        if (isset($body->link))
        {
            $item->setLink($body->link);
        }
        if (isset($body->meta))
        {
            $item->setMeta($body->meta);
        }
        $list->setItem($item);
        $em->persist($item);
        $em->flush();
        
        return true;
    }
    
    /**
     * @param int $id List id
     * @return User $user User() object
     */
    public function getUserByListId(int $id)
    {
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
    }
}