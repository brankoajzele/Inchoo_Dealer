<?php

class Inchoo_Dealer_Block_Locator_Box extends Mage_Core_Block_Template
{
    public function getFormAction()
    {
        return Mage::getUrl('dealer/locator/search');
    }
}
