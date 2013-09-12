<?php

class Inchoo_Dealer_Helper_Data extends Mage_Core_Helper_Data {

    const GOOGLE_MAPS_HOST = 'maps.googleapis.com';
    const CONFIG_PATH_DEALERS_CUSTOMER_GROUP = 'inchoo_dealer_config/dealers_customer_group/default_dealers_customer_group';
    const CONFIG_PATH_SEARCH_RADIUS_OPTIONS = 'inchoo_dealer_config/search/radius_options';
    const CONFIG_PATH_SEARCH_RADIUS_OPTION_DEFAULT = 'inchoo_dealer_config/search/default_radius_option';
    const CONFIG_PATH_SEARCH_BOX_SEARCH_TYPE_DEFAULT = 'inchoo_dealer_config/search/default_box_search_type';
    const CONFIG_PATH_SEARCH_BY_ZIP = 'inchoo_dealer_config/search/by_zip';
    const CONFIG_PATH_SEARCH_BY_STATE = 'inchoo_dealer_config/search/by_state';
    const CONFIG_PATH_SEARCH_BY_COMPANY = 'inchoo_dealer_config/search/by_company';
    const SEARCH_TYPE_ZIP = 'zip';
    const SEARCH_TYPE_STATE = 'state';
    const SEARCH_TYPE_COMPANY = 'company';

    public function getSearchTypeBoxDefault() {
        return Mage::getStoreConfig(self::CONFIG_PATH_SEARCH_BOX_SEARCH_TYPE_DEFAULT);
    }

    public function getSearchTypeZip() {
        return self::SEARCH_TYPE_ZIP;
    }

    public function getSearchTypeState() {
        return self::SEARCH_TYPE_STATE;
    }

    public function getSearchTypeCompany() {
        return self::SEARCH_TYPE_COMPANY;
    }

    public function getIsSearchByZipEnabled() {
        return Mage::getStoreConfig(self::CONFIG_PATH_SEARCH_BY_ZIP);
    }

    public function getIsSearchByStateEnabled() {
        return Mage::getStoreConfig(self::CONFIG_PATH_SEARCH_BY_STATE);
    }

    public function getIsSearchByCompanyEnabled() {
        return Mage::getStoreConfig(self::CONFIG_PATH_SEARCH_BY_COMPANY);
    }

    public function getDealersCustomerGroup() {
        return Mage::getStoreConfig(self::CONFIG_PATH_DEALERS_CUSTOMER_GROUP);
    }

    public function getSearchRadiusOptions() {

        $options = explode(',', Mage::getStoreConfig(self::CONFIG_PATH_SEARCH_RADIUS_OPTIONS));
        //$array[0] = '0';
        //$options = array_merge($array, $options);
        $options[] = '0';
        return $options;
    }

    public function getSearchRadiusOptionDefault() {
        return Mage::getStoreConfig(self::CONFIG_PATH_SEARCH_RADIUS_OPTION_DEFAULT);
    }

    public function getCustomerImageUrl($object, $image, $size = null) {
        $url = false;
        //$image = $object->getImage1();

        if (!is_null($size) && file_exists(Mage::getBaseDir('media') . DS . 'customer' . DS . $size . $image)) {
            # resized image is cached
            $url = Mage::app()->getStore($object->getStore())->getBaseUrl('media') . 'customer/' . $size . $image;
        } elseif (!is_null($size)) {
            # resized image is not cached
            $url = Mage::app()->getStore($object->getStore())->getBaseUrl() . 'customer/image/size/' . $size . $image;
        } elseif ($image) {
            # using original image
            $url = Mage::app()->getStore($object->getStore())->getBaseUrl('media') . 'customer' . $image;
        }
        return $url;
    }

    /**
     *
     * @param Mage_Customer_Model_Address $address
     * @param boolean $saveCoordinatesToAddress
     * @return array Returns the array of float values like {[0] => float(18.4438156) [1] => float(45.5013644)}
     */
    public function fetchAddressGeoCoordinates(Mage_Customer_Model_Address $address, $saveCoordinatesToAddress = true) {
        $coordinates = array();

        $lineAddress = $address->getStreet1() . ', ' . $address->getPostcode() . ' ' . $address->getCity() . ', ' . $address->getCountry();


        /*
         * Google Geocoding API (V3)
         * http://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&sensor=true_or_false
         * 
         * V3 API Doesnt need API key for retrieving location for address unlike V2 API.
         * 
         */

        $client = new Zend_Http_Client();
        $client->setUri('http://' . self::GOOGLE_MAPS_HOST . '/maps/api/geocode/json');
        $client->setMethod(Zend_Http_Client::GET);
        $client->setConfig(array('maxredirects' => 0, 'timeout' => 60));
        $client->setParameterGet('address', $lineAddress);
        $client->setParameterGet('sensor', 'false');

        $response = $client->request();

        if ($response->isSuccessful() && $response->getStatus() == 200) {
            $_response = json_decode($response->getBody());
            $_status = @$_response->status;

            if ($_status == 'OK') {
                $_location = @$_response->results[0]->geometry->location;
                $_lat = $_location->lat;
                $_lng = $_location->lng;

                if ($_lat && $_lng) {

                    $coordinates = array($_lat, $_lng);

                    if ($saveCoordinatesToAddress) {
                        try {
                            $address->setInchooGeoLatitude($_lat);
                            $address->setInchooGeoLongitude($_lng);
                        } catch (Exception $e) {
                            Mage::logException($e);
                        }
                    }
                }
            }
        }

        return $coordinates;
    }

    /* Get only the regions for which there are dealers */

    public function getDealerRegions() {
        $customerGroup = Mage::getModel('customer/group');
        $customerGroup->load($this->getDealersCustomerGroup(), 'customer_group_code');

        $collection = Mage::getResourceModel('customer/customer_collection')
                ->addAttributeToFilter('group_id', array('eq' => $customerGroup->getId()))
                ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left');

        $collection->getSelect()
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns('at_billing_region.value');

        $collection->getSelect()->distinct();

        $regions = array();

        foreach ($collection as $region) {
            $_region = trim($region->getValue());
            if (!empty($_region)) {
                $regions[] = $_region;
            }
        }

        sort($regions);

        return $regions;
    }

    /* Get only the regions for which there are dealers */

    public function getDealerCompanies() {
        $customerGroup = Mage::getModel('customer/group');
        $customerGroup->load($this->getDealersCustomerGroup(), 'customer_group_code');

        $collection = Mage::getResourceModel('customer/customer_collection')
                ->addAttributeToFilter('group_id', array('eq' => $customerGroup->getId()))
                ->joinAttribute('billing_company', 'customer_address/company', 'default_billing', null, 'left');

        $collection->getSelect()
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns('at_billing_company.value');

        $collection->getSelect()->distinct();

        $companies = array();

        foreach ($collection as $company) {
            $_company = trim($company->getValue());
            if (!empty($_company)) {
                $companies[] = $_company;
            }
        }

        sort($companies);

        return $companies;
    }

    public function getNearbyDealersLocations($radius, $centerLat, $centerLng) {
        $customerGroup = Mage::getModel('customer/group');
        $customerGroup->load($this->getDealersCustomerGroup(), 'customer_group_code');

        if (!$customerGroup->getId()) {
            throw new Exception($this->__('Unable to load the customer group.'));
        }

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
                ->joinAttribute('billing_company', 'customer_address/company', 'default_billing', null, 'left')
                ->joinAttribute('inchoo_geo_latitude', 'customer_address/inchoo_geo_latitude', 'default_billing', null, 'left')
                ->joinAttribute('inchoo_geo_longitude', 'customer_address/inchoo_geo_longitude', 'default_billing', null, 'left')
                ->addAttributeToFilter('inchoo_geo_latitude', array('notnull' => true))
                ->addAttributeToFilter('inchoo_geo_longitude', array('notnull' => true))
                ->addExpressionAttributeToSelect('distance', sprintf("(3959 * acos(cos(radians('%s')) * cos(radians(at_inchoo_geo_latitude.value)) * cos(radians(at_inchoo_geo_longitude.value) - radians('%s')) + sin(radians('%s')) * sin( radians(at_inchoo_geo_latitude.value))))", $centerLat, $centerLng, $centerLat, $radius), array('entity_id'))
                ->addAttributeToSort('billing_company', 'ASC')
                ->addAttributeToSort('billing_city', 'ASC');

        if ($radius !== 0) {
            $collection->getSelect()->having('distance < ?', $radius);
        }

        $collection->getSelect()->order('distance ' . Varien_Db_Select::SQL_ASC);
        //echo (string)$collection->getSelect(); exit;

        return $collection;
    }

}