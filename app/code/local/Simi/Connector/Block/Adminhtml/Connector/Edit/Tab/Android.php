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
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Android extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare tab form's information
     *
     * @return Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $data = array();        
        $data['android_key'] = Mage::getStoreConfig('connector/android_key');
		$data['android_sendid'] = Mage::getStoreConfig('connector/android_sendid');
        $fieldset = $form->addFieldset('connector_android', array('legend' => Mage::helper('connector')->__('Key Notification')));

        $fieldset->addField('android_key', 'password', array(
            'label' => Mage::helper('connector')->__('Key'),            
            'name' => 'android_key',
            'note' => 'Key to send Notification Android'
        ));
		
		$fieldset->addField('android_sendid', 'password', array(
            'label' => Mage::helper('connector')->__('Sender Id'),            
            'name' => 'android_sendid',
            'note' => 'Id to send Notification Android'
        ));
        $form->setValues($data);
        return parent::_prepareForm();
    }

}