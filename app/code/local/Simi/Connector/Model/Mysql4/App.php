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
 * @package 	Magestore_Madapter
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

 /**
 * Madapter Resource Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Simi_Connector_Model_Mysql4_App extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('connector/app', 'app_id');
	}
}