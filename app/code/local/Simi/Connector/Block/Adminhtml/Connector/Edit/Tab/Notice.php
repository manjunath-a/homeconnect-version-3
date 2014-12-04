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
 * Connector Grid Block
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Notice extends Mage_Adminhtml_Block_Widget_Form {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('connector/edit/tab/notice.phtml');
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
    }
    
    public function getDataNotice(){        
        $device_id = $this->getRequest()->getParam('device_id');
        $web_id = $this->getRequest()->getParam('website');
        return Mage::getModel('connector/notice')->getDataNotice($device_id, $web_id);
    }
    
    public function getWebId(){        
        return $this->getRequest()->getParam('website');
    }
    
    public function getDeviceId(){
        return $this->getRequest()->getParam('device_id');
    }
}