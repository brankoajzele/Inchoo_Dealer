<?php

class Inchoo_Dealer_Model_Config_Group_Options 
{
    public function toOptionArray() {

        $customer_group = new Mage_Customer_Model_Group();
        
        $allGroups = $customer_group->getCollection()->toOptionHash();
        
        foreach ($allGroups as $key => $group) {
            $customerGroup[$key] = array('value' => $group, 'label' => $group);
        }
        
        return $customerGroup;
    }
}

