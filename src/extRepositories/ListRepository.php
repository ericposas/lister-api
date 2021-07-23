<?php

namespace PHPapp\ExtendedRepositories;

class ListRepository extends \Doctrine\ORM\EntityRepository
{
    
//    public function populateListData($list, array $listData)
//    {
//        $listData["id"] = $list->getId();
//        $listData["name"] = $list->getName();
//
//        $items = $list->getItems();
//        foreach ($items as $item) {
//            $itemData[] = $this->getEntityManager()
//                    ->getRepository(\PHPapp\Entity\Item::class)
//                    ->dynamicGetAllItemProperties($item);
//        }
//
//        $listData["items"] = $itemData;
//        
//        return $listData;
//    }
    
}
