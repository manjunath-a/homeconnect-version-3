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
 * Connector Model
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Device extends Simi_Connector_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('connector/device');
    }

    public function setDataDevice($data, $device_id) {
        $website = Mage::app()->getStore()->getWebsiteId();        
        $this->setData('device_token', $data->device_token);
        $this->setData('plaform_id', $device_id);
        $this->setData('website_id', $website);
        try {
            $this->save();
            $information = $this->statusSuccess();
            return $information;
        } catch (Exception $e) {
            if (is_array($e->getMessage())) {
                $information = $this->statusError($e->getMessage());
                return $information;
            } else {
                $information = $this->statusError(array($e->getMessage()));
                return $information;
            }
        }
    }

}