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
 * @category 	Simi
 * @package 	Simi_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Mconnect Index Controller
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_CatalogController extends Simi_Connector_Controller_Action {

    /**
     * index action
     */
    protected function _helper() {
        return Mage::helper('connector');
    }

    public function indexAction() {
        echo "Not Thing!";
        $this->_helper()->convertToPlist();
        die();
    }

    public function get_spot_productsAction() {
        $data = $this->getData();
        $information = Mage::getModel('connector/catalog_product')->getSpotProduct($data);
        $this->_printDataJson($information);
    }

    public function get_all_productsAction() {
        $data = $this->getData();
        $information = Mage::getModel('connector/catalog_product')->getAllProducts($data);
        $this->_printDataJson($information);
    }

    public function get_product_detailAction() {
        $data = $this->getData();
        $information = Mage::getModel('connector/catalog_product')->getDetail($data);
        $this->_printDataJson($information);
    }

    public function get_related_productsAction() {
        $data = $this->getData();
        $information = Mage::getModel('connector/catalog_product')->getRelatedProducts($data);
        $this->_printDataJson($information);
    }

    public function search_productsAction() {
        $data = $this->getData();        
        $information = Mage::getModel('connector/catalog_product')->getSearchProducts($data);
        $this->_printDataJson($information);
    }

    public function get_categoriesAction() {
        $data = $this->getData();        
		$device_id = $this->getDeviceId();		
        $information = Mage::getModel('connector/catalog_category')->getCategories($data, $device_id);
        $this->_printDataJson($information);
    }

    public function get_category_productsAction() {
        $data = $this->getData();               
        $information = Mage::getModel('connector/catalog_product')->getCategoryProduct($data);
        $this->_printDataJson($information);
    }
    
    public function get_product_reviewAction(){
        $data = $this->getData();
        $information = Mage::getModel('connector/catalog_product')->getProductReview($data);
        $this->_printDataJson($information);
    }
}