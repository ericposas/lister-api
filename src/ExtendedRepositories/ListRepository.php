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
                "owner" => $list->getOwner()->getName(),
                "id" => $list->getId(),
                "name" => $list->getName(),
                "items" => $itemData
            ];
        }
        return $listData;
    }
    
}
