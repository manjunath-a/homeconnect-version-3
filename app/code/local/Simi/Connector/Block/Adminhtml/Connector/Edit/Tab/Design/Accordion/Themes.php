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
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Design_Accordion_Themes extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * Getter for accordion item title
     *
     * @return string
     */
    public function getTitle() {
        return $this->__('Theme Color');
    }

    /**
     * Getter for accordion item is open flag
     *
     * @return bool
     */
    public function getIsOpen() {
        return true;
    }

    public function __construct() {
        parent::__construct();
        $this->setTemplate('connector/edit/tab/design/themes.phtml');
    }

    public function getId() {
        return $this->getRequest()->getParam('id');
    }

    public function getDeviceId() {
        $id = $this->getId();
        $device_id = Mage::getModel('connector/app')->load($id)->getDeviceId();
        return $device_id;
    }

    public function getWebsiteId() {
        return $this->getRequest()->getParam('website');
    }

    public function getThemeColorValue() {
        $value = Mage::getModel('connector/design')->getCollection()
                        ->addFieldToFilter('website_id', $this->getWebsiteId())
                        ->addFieldToFilter('device_id', $this->getDeviceId())
                        ->getFirstItem()->getThemeColor();
        return $value;
    }

}