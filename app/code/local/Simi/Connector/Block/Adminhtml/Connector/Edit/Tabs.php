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
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('connector_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('connector')->__('Manage Mobile App'));
    }

    protected function _prepareLayout() {
        return parent::_prepareLayout();
    }

    /**
     * prepare before render block to html
     *
     * @return Simi_Connector_Block_Adminhtml_Connector_Edit_Tabs
     */
    protected function _beforeToHtml() {
        $this->addTab('plugin_section', array(
            'label' => Mage::helper('connector')->__('Manage Plugins'),
            'title' => Mage::helper('connector')->__('Manage Plugins'),
            'url' => $this->getUrl('*/*/plugin', array('_current' => true, 'id' => $this->getRequest()->getParam('id'))),
            'class' => 'ajax',
        ));
		
		$this->addTab('categories_section', array(
           'label' => Mage::helper('connector')->__('Manage Categories'),
           'title' => Mage::helper('connector')->__('Manage Categories'),
           'url'       => $this->getUrl('*/*/categories', array('_current' => true)),
                        'class'     => 'ajax'));
//        $this->addTab('localize_section', array(
//            'label' => Mage::helper('connector')->__('Localize'),
//            'title' => Mage::helper('connector')->__('Localize'),
//            'content' => $this->getLayout()->createBlock('connector/adminhtml_connector_edit_tab_localize')->toHtml(),
//        ));
        $id = $this->getRequest()->getParam('device_id');
        if ($id == "1" || $id == "2") {
            $this->addTab('pem_section', array(
                'label' => Mage::helper('connector')->__('Upload PEM File'),
                'title' => Mage::helper('connector')->__('Upload PEM File'),
                'content' => $this->getLayout()->createBlock('connector/adminhtml_connector_edit_tab_pem')->toHtml(),            
				'active' => true,
            ));
        }
//        $this->addTab('notification_section', array(
//            'label' => Mage::helper('connector')->__('Push Notification'),
//            'title' => Mage::helper('connector')->__('Push Notification'),
//            'content' => $this->getLayout()->createBlock('connector/adminhtml_connector_edit_tab_notice')->toHtml(),
//        ));
        if ($id == "3") {
            $this->addTab('keyapp_section', array(
                'label' => Mage::helper('connector')->__('Key app for Notification'),
                'title' => Mage::helper('connector')->__('Key app for Notification'),
                'content' => $this->getLayout()->createBlock('connector/adminhtml_connector_edit_tab_android')->toHtml(),
				'active' => true,
            ));
        }

        return parent::_beforeToHtml();
    }

}