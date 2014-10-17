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
 * Connector Edit Tabs Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @author  	Magestore Developer
 */
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Design extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    public function __construct() {
        parent::__construct();
        $this->setShowGlobalIcon(true);
        $this->setTemplate('connector/edit/tab/design.phtml');
    }

    public function canShowTab() {
        return false;
    }

    public function getTabLabel() {
        return $this->__('Design');
    }

    public function getTabTitle() {
        return $this->__('Design');
    }

    public function isHidden() {
        return true;
    }

}