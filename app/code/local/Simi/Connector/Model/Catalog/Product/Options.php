<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Model
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Catalog_Product_Options {

    public function getOptions($product) {
        $type = $product->getTypeId();
        switch ($type) {
            case Mage_Catalog_Model_Product_Type::TYPE_SIMPLE:
                return Mage::getModel('connector/catalog_product_options_simple')->getOptions($product);
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE :
                return Mage::getModel('connector/catalog_product_options_bundle')->getOptions($product);
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE :
                return Mage::getModel('connector/catalog_product_options_configurable')->getOptions($product);
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED :
                return Mage::getModel('connector/catalog_product_options_grouped')->getOptions($product);
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL :
                return Mage::getModel('connector/catalog_product_options_virtual')->getOptions($product);
                break;
			// case "downloadable" :
                // return Mage::getModel('connector/catalog_product_options_downloadable')->getOptions($product);
                // break;
        }
    }

    public function getPriceModel($product) {
        $type = $product->getTypeId();
        switch ($type) {          
            case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE :
                return Mage::getSingleton('connector/catalog_product_options_bundle')->getPrice($product);
                break;            
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED :
                return Mage::getSingleton('connector/catalog_product_options_grouped')->getPrice($product);
                break;            
        }
    }

}

?>
