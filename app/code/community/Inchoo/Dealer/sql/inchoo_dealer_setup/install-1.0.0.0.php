<?php

$installer = $this;
$installer->startSetup();

$setup = Mage::getModel('customer/entity_setup', 'core_setup');

$setup->addAttribute('customer_address', 'inchoo_geo_latitude', array(
    'type' => 'varchar',
    'input' => 'text',
    'visible' => true,
    'required' => false,
    'backend_label' => 'Inchoo Geo Latitude',
    'label' => 'Inchoo Geo Latitude',
    'frontend' => '',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

$setup->addAttribute('customer_address', 'inchoo_geo_longitude', array(
    'type' => 'varchar',
    'input' => 'text',
    'visible' => true,
    'required' => false,
    'backend_label' => 'Inchoo Geo Longitude',
    'label' => 'Inchoo Geo Longitude',
    'frontend' => '',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

/*
 * Adding additional fields to edit customer admin area.
 */
$setup->addAttribute('customer', 'website_url', array(
    'backend_label' => 'Website Url',
    'label' => 'Website Url',
    'type' => 'varchar',
    'frontend' => '',
    'input' => 'text',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));


$setup->addAttribute('customer', 'image1', array(
    'backend_label' => 'Image 1',
    'label' => 'Image 1',
    'type' => 'varchar',
    'frontend' => '',
    'input' => 'image',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

$setup->addAttribute('customer', 'image2', array(
    'backend_label' => 'Image 2',
    'label' => 'Image 2',
    'type' => 'varchar',
    'frontend' => '',
    'input' => 'image',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

$setup->addAttribute('customer', 'image3', array(
    'backend_label' => 'Image 3',
    'label' => 'Image 3',
    'type' => 'varchar',
    'frontend' => '',
    'input' => 'image',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

$setup->addAttribute('customer', 'image4', array(
    'backend_label' => 'Image 4',
    'label' => 'Image 4',
    'type' => 'varchar',
    'frontend' => '',
    'input' => 'image',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

$setup->addAttribute('customer', 'image5', array(
    'backend_label' => 'Image 5',
    'label' => 'Image 5',
    'type' => 'varchar',
    'frontend' => '',
    'input' => 'image',
    'class' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => true,
    'required' => false,
    'user_defined' => false,
    'default' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
));

$_config = Mage::getSingleton("eav/config");

$websiteUrl = $_config->getAttribute("customer", "website_url");
$image1 = $_config->getAttribute("customer", "image1");
$image2 = $_config->getAttribute("customer", "image2");
$image3 = $_config->getAttribute("customer", "image3");
$image4 = $_config->getAttribute("customer", "image4");
$image5 = $_config->getAttribute("customer", "image5");
$geoLatitude = $_config->getAttribute("customer_address", "inchoo_geo_latitude");
$geoLongitude = $_config->getAttribute("customer_address", "inchoo_geo_longitude");

$used_in_forms = array();
$used_in_forms[] = "adminhtml_customer";
$used_in_forms[] = "checkout_register";
$used_in_forms[] = "customer_account_create";
$used_in_forms[] = "customer_account_edit";
$used_in_forms[] = "adminhtml_checkout";
$used_in_forms[] = "adminhtml_customer_address";

/*
 * Customer Address Admin
 */
$geoLatitude->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 200);
$geoLatitude->save();

$geoLongitude->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 201);
$geoLongitude->save();

/*
 * Customer Admin
 */
$websiteUrl->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 99);
$websiteUrl->save();

$image1->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 100);
$image1->save();

$image2->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 101);
$image2->save();

$image3->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 102);
$image3->save();

$image4->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 103);
$image4->save();

$image5->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 104);
$image5->save();

$installer->endSetup();
