<?php

class Inchoo_Dealer_LocatorController extends Mage_Core_Controller_Front_Action {

    public function searchAction() {
        $helper = Mage::helper('inchoo_dealer');

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $searchTypeZip = $helper->getSearchTypeZip();
        $searchTypeState = $helper->getSearchTypeState();
        $searchTypeCompany = $helper->getSearchTypeCompany();

        $searchRequest = $this->getRequest();
        $searchType = $searchRequest->getParam('search_type', false);
        $state = $searchRequest->getParam($searchTypeState, false);
        $company = $searchRequest->getParam($searchTypeCompany, false);
        $zip = $searchRequest->getParam($searchTypeZip, false);
        $radius = $searchRequest->getParam('radius', false);

        if (isset($radius) && $radius == '0') {
            $radius = 0;
        } else {
            $radius = (int) $radius;
        }

        Mage::register('dealer_search_request', $searchRequest);

        if (($searchType == $searchTypeState) && !empty($state)) {
            $customerGroup = Mage::getModel('customer/group');
            $customerGroup->load($helper->getDealersCustomerGroup(), 'customer_group_code');

            $collection = Mage::getResourceModel('customer/customer_collection')
                    ->addNameToSelect()
                    ->addAttributeToSelect('email')
                    ->addAttributeToSelect('website_url')
                    ->addAttributeToFilter('group_id', array('eq' => $customerGroup->getId()))
                    ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                    ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                    ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                    ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
                    ->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
                    ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                    ->joinAttribute('billing_company', 'customer_address/company', 'default_billing', null, 'left')
                    ->joinAttribute('inchoo_geo_latitude', 'customer_address/inchoo_geo_latitude', 'default_billing', null, 'left')
                    ->joinAttribute('inchoo_geo_longitude', 'customer_address/inchoo_geo_longitude', 'default_billing', null, 'left')
                    //->addAttributeToFilter('inchoo_geo_latitude', array('notnull'=>true))
                    //->addAttributeToFilter('inchoo_geo_longitude', array('notnull'=>true))
                    ->addAttributeToFilter('billing_region', array('like' => '%' . $state . '%'))
                    ->addAttributeToSort('billing_company', 'ASC')
                    ->addAttributeToSort('billing_city', 'ASC');

            Mage::register('dealer_search_result', $collection);
        } else if (($searchType == $searchTypeCompany) && !empty($company)) {
            $customerGroup = Mage::getModel('customer/group');
            $customerGroup->load($helper->getDealersCustomerGroup(), 'customer_group_code');

            $collection = Mage::getResourceModel('customer/customer_collection')
                    ->addNameToSelect()
                    ->addAttributeToSelect('email')
                    ->addAttributeToSelect('website_url')
                    ->addAttributeToFilter('group_id', array('eq' => $customerGroup->getId()))
                    ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                    ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                    ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                    ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
                    ->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
                    ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                    ->joinAttribute('billing_company', 'customer_address/company', 'default_billing', null, 'left')
                    ->joinAttribute('inchoo_geo_latitude', 'customer_address/inchoo_geo_latitude', 'default_billing', null, 'left')
                    ->joinAttribute('inchoo_geo_longitude', 'customer_address/inchoo_geo_longitude', 'default_billing', null, 'left')
                    //->addAttributeToFilter('inchoo_geo_latitude', array('notnull'=>true))
                    //->addAttributeToFilter('inchoo_geo_longitude', array('notnull'=>true))
                    ->addAttributeToFilter('billing_company', array('like' => '%' . $company . '%'))
                    ->addAttributeToSort('billing_city', 'ASC');

            Mage::register('dealer_search_result', $collection);
        } else if (($searchType == $searchTypeZip) && ($radius >= 0)) {


            /* Fake an address based on the provided ZIP */
            $currentAddress = new Mage_Customer_Model_Address();
            $currentAddress->setPostcode($zip);
            $currentAddress->setCountryId('US');
            /* get GEO for entire faked address/ZIP */
            $centerCoordinates = $helper->fetchAddressGeoCoordinates($currentAddress, false);

            $centerLat = $centerCoordinates[0];
            $centerLng = $centerCoordinates[1];

            $collection = $helper->getNearbyDealersLocations($radius, $centerLat, $centerLng);

            Mage::register('dealer_search_center_lat', $centerLat);
            Mage::register('dealer_search_center_lon', $centerLng);
            Mage::register('dealer_search_result', $collection);
        }

        $this->renderLayout();
    }

    public function searchPost() {
        if ($this->getRequest()->isPost()) {
            $this->_redirect('');
            return;
        }

        $this->_redirectReferer();
    }

    public function resaveAction() {
        $has_error = false;

        $dealers = Mage::getModel('customer/customer')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('group_id', array(
                    'eq' => 4
                ))
                ->addAttributeToFilter('website_id', array(
            'eq' => 1
        ));

        foreach ($dealers as $dealer) {
            try {
                $dealer->save();
            } catch (Exception $e) {
                Mage::log(sprintf('Unable to save dealer #%s with the following email address: %s', $dealer->getId(), $dealer->getEmail()), null, 'dealers_resave.log');
                $has_error = true;
            }
        }

        if (!$has_error) {
            Mage::getSingleton('core/session')->addSuccess('Successfully re-saved the dealers.');
        } else {
            Mage::getSingleton('core/session')->addError('There were some errors while re-saving the dealers. Please check the log file.');
        }

        return $this->_redirect('dealer/locator/search');
    }

}
