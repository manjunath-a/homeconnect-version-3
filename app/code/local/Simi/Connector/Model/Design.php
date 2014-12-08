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
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @author  	Magestore Developer
 */
class Simi_Connector_Model_Design extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('connector/design');
    }
	
	public function getThemeByWebDevice($web_id, $device_id){
		$collection = $this->getCollection()
			->addFieldToFilter('website_id', array('eq' => $web_id))
			->addFieldToFilter('device_id', array('eq' => $device_id));
		return $collection->getFirstItem();
	}
	public function setTheme($web_id, $device_id, $color){
		$item = $this->getThemeByWebDevice($web_id, $device_id);
		$this->setData('theme_color', $color);
		$this->setId($item->getId())->save();
	}

}