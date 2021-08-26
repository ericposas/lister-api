<?php

namespace PHPapp\ExtendedRepositories;

use Doctrine\DBAL\Driver\Exception;

class ListRepository extends \Doctrine\ORM\EntityRepository
{
    
    protected \Doctrine\ORM\EntityManager $entityManager;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->entityManager = $em;
    }

    public function updateList($id, $body)
    {
        $list = $this->find($id);
        
        function setItems($list, $body, \Doctrine\ORM\EntityManager $em) {
            if (isset($body->items)) {
                foreach ($list->getItems() as $listItem) {
                    $listItems[] = $listItem->getName();
                }
                # if item is not already in list, add a new item
                foreach ($body->items as $name) {
                    if (! in_array($name, $listItems)) {
                        $item = new \PHPapp\Entity\Item();
                        $item->setName($name);
                        $item->addParentlist($list);
                        $em->persist($item);
                    }
                }
            }
        }
        
        if (isset($list)) {
            try {
                isset($body->name) ? $list->setName($body->name) : "";
                isset($body->description) ? $list->setDescription($body->description) : "";
                setItems($list, $body, $this->entityManager);
                $this->entityManager->flush();
                return [
                    "status" => "success",
                    "user" => $list->getOwner()
                ];
            } catch (Exception $ex) {
                throw new Exception($ex);
            }
        }
        
        return [
            "status" => "failed",
            "user" => null
        ];
    }
    
    public function getListData($lists)
    {
        foreach ($lists as $list) {
            $items = $list->getItems();
            $itemRepo = $this->getEntityManager()
                    ->getRepository(\PHPapp\Entity\Item::class);
            $itemData = $itemRepo->getItemData($items);

            $listData[] = [
                "id" => $list->getId(),
                "owner" => $list->getOwner()->getName(),
                "name" => $list->getName(),
                "description" => $list->getDescription(),
                "items" => $itemData
            ];
        }
        return $listData;
    }
    
}
