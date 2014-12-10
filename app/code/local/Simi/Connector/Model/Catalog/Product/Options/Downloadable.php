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
class Simi_Connector_Model_Catalog_Product_Options_Downloadable extends Simi_Connector_Model_Abstract
{

   public function getOptions($product) {
       $information = array();
	   Zend_debug::dump(get_class_methods($product));
	   Zend_debug::dump($product->getData());
	   foreach ($product->getOptions() as $_option) {
			Zend_Debug::dump($_option->getData());
	   }	   
	   die('xxxxxxxxxx');
       return $information;
   }

}
