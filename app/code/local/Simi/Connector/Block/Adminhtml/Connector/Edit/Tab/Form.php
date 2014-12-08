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
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    /**
     * prepare tab form's information
     *
     * @return Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $data = array();
        $website_id = $this->getRequest()->getParam('website');
        $websitename = Mage::getSingleton('core/website')->load($website_id)->getName();
        $data['secret_key'] = Mage::getModel('connector/key')->getKey($website_id)->getKeySecret();

        $fieldset = $form->addFieldset('connector_form', array('legend' => Mage::helper('connector')->__('App information')));

        $fieldset->addField('secret_key', 'password', array(
            'label' => Mage::helper('connector')->__('Secret Key'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'secret_key',
            'note' => 'You must enter key is correct!. The key will be used for all devices and '.$websitename
        ));
        $form->setValues($data);
        return parent::_prepareForm();
    }

    public function canShowTab() {
        return true;
    }

    public function getTabLabel() {
        return $this->__('General');
    }

    public function getTabTitle() {
        return $this->__('General');
    }

    public function isHidden() {
        return FALSE;
    }

}