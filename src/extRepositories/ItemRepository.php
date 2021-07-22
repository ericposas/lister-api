<?php

namespace PHPapp\ExtendedRepositories;


class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function populateItemProperties($newItem, $body)
    {
        $props = [ "Icon", "Image", "Link", "Meta", "Name" ];
        # Loop through props and dynamically call methods to set each 
        foreach ($props as $prop) {
            $bodyProp = strtolower($prop);
            $gotProp = $body->{$bodyProp};
            $_setProp = "set{$prop}";
            if (isset($gotProp)) {
                $newItem->{$_setProp}($gotProp);
            }
        }
        return $newItem;
        
    }
    
}
