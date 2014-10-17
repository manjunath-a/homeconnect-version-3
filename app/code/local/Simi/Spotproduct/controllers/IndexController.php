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
 * @package 	Magestore_Spotproduct
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Spotproduct Index Controller
 * 
 * @category 	Magestore
 * @package 	Magestore_Spotproduct
 * @author  	Magestore Developer
 */
class Simi_Spotproduct_IndexController extends Simi_Connector_Controller_Action {

    /**
     * index action
     */
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function get_spot_productsAction(){
        $data = $this->getData();        
        $information = Mage::getModel('spotproduct/spotproduct')->getSpotProduct($data);
        $this->_printDataJson($information);        
    }
	
	public function get_spot_products_v2Action(){
        $data = $this->getData();         
        $information = Mage::getModel('spotproduct/spotproduct')->getSpotProducts($data);
        $this->_printDataJson($information);        
    }
}