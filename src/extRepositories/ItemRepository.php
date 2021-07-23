<?php

namespace PHPapp\ExtendedRepositories;


class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    
    static $props = [ "Id", "Icon", "Image", "Link", "Meta", "Name" ];
    
    # Below methods seem to break if the loop is outside of this scope
    
    /**
     * @param Item $item Takes in an Item() instance and gets all properties, assigns them to an array of data
     * to send back in the response object 
     * @return array returns an array of data mapped from an Item instance 
     */
    public function dynamicGetAllItemProperties($item)
    {
        foreach (ItemRepository::$props as $prop) {
            $bprop = strtolower($prop);
            $getProp = "get{$prop}";
            $properties["{$bprop}"] = $item->{$getProp}();
        }
        return $properties;
    }
    
    /**
     * @param requestBody $body Takes in a request body with any item props { name, icon, image, link, meta }
     * @return Item returns the updated Item class instance 
     */
    public function dynamicSetAllItemProperties($newItem, $body)
    {
        # Loop through props and dynamically call methods to set each 
        foreach (ItemRepository::$props as $prop) {
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
