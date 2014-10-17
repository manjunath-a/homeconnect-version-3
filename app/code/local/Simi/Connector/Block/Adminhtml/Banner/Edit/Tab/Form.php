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
 * Simi Edit Form Content Tab Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Simi_Connector_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare tab form's information
     *
     * @return Simi_Connector_Block_Adminhtml_Banner_Edit_Tab_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getConnectorData()) {
            $data = Mage::getSingleton('adminhtml/session')->getConnectorData();
            Mage::getSingleton('adminhtml/session')->setConnectorData(null);
        } elseif (Mage::registry('banner_data'))
            $data = Mage::registry('banner_data')->getData();

        $fieldset = $form->addFieldset('connector_form', array('legend' => Mage::helper('connector')->__('Banner information')));

        $fieldset->addField('website_id', 'select', array(
            'label' => Mage::helper('connector')->__('Choose website'),
            'name' => 'website_id',
            'values' => Mage::getSingleton('connector/status')->getWebsite(),
        ));


        $fieldset->addField('banner_title', 'text', array(
            'label' => Mage::helper('connector')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'banner_title',
        ));

        if (isset($data['banner_name']) && $data['banner_name']) {

            $data['banner_name'] = Mage::getBaseUrl('media') . 'simi/simicart/banner/' . $data['website_id'] . '/' . $data['banner_name'];
        }
        $fieldset->addField('banner_name', 'image', array(
            'label' => Mage::helper('connector')->__('Image (width:640px, height:180px)'),
            'required' => FALSE,
            'name' => 'banner_name_co',
        ));

        $fieldset->addField('banner_url', 'editor', array(
            'name' => 'banner_url',           
            'label' => Mage::helper('connector')->__('Url'),
            'title' => Mage::helper('connector')->__('Url'),
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('connector')->__('Status'),
            'name' => 'status',
            'values' => Mage::getSingleton('connector/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }

}