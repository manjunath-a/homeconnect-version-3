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
 * Connector Edit Form Content Tab Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @author  	Magestore Developer
 */
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Design_Preview extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $template = '';
        $device_id = $this->getDeviceId();
        if ($device_id == "1") {
            $template = 'connector/edit/tab/design/preview_iphone.phtml';
        } elseif ($device_id == "2") {
            $template = 'connector/edit/tab/design/preview_ipad.phtml';
        } elseif ($device_id == "3") {
            $template = 'connector/edit/tab/design/preview_android.phtml';
        } elseif ($device_id == "4") {
            $template = 'connector/edit/tab/design/preview_winphone.phtml';
        }
        $this->setTemplate($template);
    }

    public function getSkinUrlSimi() {
        return $this->getSkinUrl() . 'simi/connector/images/';
    }

    public function getId() {
        return $this->getRequest()->getParam('id');
    }

    public function getDeviceId() {        
        return $this->getRequest()->getParam('device_id');
    }

    public function getWebsiteId() {
        return $this->getRequest()->getParam('website');
    }

    public function getMiddlePath() {                        
        return $this->getWebsiteId();
    }

    public function getThemeColorValue() {
        $value = Mage::getModel('connector/design')->getCollection()
                        ->addFieldToFilter('device_id', $this->getDeviceId())
                        ->addFieldToFilter('website_id', $this->getWebsiteId())
                        ->getFirstItem()->getThemeColor();
        return $value;
    }

    public function getLogoImage() {
        return Mage::helper('connector')->getLogoImage($this->getMiddlePath());
    }

}
