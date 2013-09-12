<?php

class Inchoo_Dealer_Block_Locator_Search extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('inchoo/dealer/locator/search.phtml');
    }
    
    public function getLoadedPostCollection()
    {
        return $this->loaded_dealer_collection;
    }    
    
    public function getFormAction() 
    {
        return Mage::getUrl('dealer/locator/search');
    }
}
