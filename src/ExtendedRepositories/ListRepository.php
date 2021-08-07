<?php

namespace PHPapp\ExtendedRepositories;

class ListRepository extends \Doctrine\ORM\EntityRepository
{
    
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
