<?php

class Inchoo_Dealer_Model_Config_Searchtypes_Options 
{
    public function toOptionArray() {

        $zipKey = Inchoo_Dealer_Helper_Data::SEARCH_TYPE_ZIP;
        $customerGroup[0] = array('value' => $zipKey, 'label' => ucfirst($zipKey));
        
        $stateKey = Inchoo_Dealer_Helper_Data::SEARCH_TYPE_STATE;
        $customerGroup[1] = array('value' => $stateKey, 'label' => ucfirst($stateKey));
        
        $companyKey = Inchoo_Dealer_Helper_Data::SEARCH_TYPE_COMPANY;
        $customerGroup[2] = array('value' => $companyKey, 'label' => ucfirst($companyKey));
        
        return $customerGroup;
    }
}

