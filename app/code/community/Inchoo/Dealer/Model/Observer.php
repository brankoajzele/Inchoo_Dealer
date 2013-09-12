<?php

class Inchoo_Dealer_Model_Observer {

    public function injectLatLongIntoAddress($observer) {
        Mage::helper('inchoo_dealer')
                ->fetchAddressGeoCoordinates(
                        $observer->getEvent()->getCustomerAddress()
        );
    }

}
