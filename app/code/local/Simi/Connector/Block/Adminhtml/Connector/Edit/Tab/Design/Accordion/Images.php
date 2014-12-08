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
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Design_Accordion_Images extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * Getter for accordion item title
     *
     * @return string
     */
    public function getTitle() {
        return $this->__('Images');
    }

    /**
     * Getter for accordion item is open flag
     *
     * @return bool
     */
    public function getIsOpen() {
        return true;
    }

    public function getMiddlePath() {      
        return $this->getRequest()->getParam('website');
    }

    protected function _prepareForm() {
        $path = Mage::helper('connector')->getDirLogoImage($this->getMiddlePath());
        $form = new Varien_Data_Form();
        $this->setForm($form);
//        if (Mage::getSingleton('adminhtml/session')->getConnectorData()) {
//            $data = Mage::getSingleton('adminhtml/session')->getConnectorData();
//            Mage::getSingleton('adminhtml/session')->setConnectorData(null);
//        } elseif (Mage::registry('connector_data'))
//            $data = Mage::registry('connector_data')->getData();

        $fieldset = $form->addFieldset('connector_images', array());
        $data = array();
        $name = 'theme_logo';
        if (file_exists($path)) {
            $data['theme_logo'] = Mage::helper('connector')->getLogoImage($this->getMiddlePath());
            $fieldset->addField($name, 'image', array(
                'label' => Mage::helper('connector')->__('Logo'),
                'name' => $name,
                //'value' => ,
                'note' => 'Recommended size 640px x 180px. Allow .png file type'
            ));
        } else {
            $fieldset->addField($name, 'image', array(
                'label' => Mage::helper('connector')->__('Logo'),
                'name' => $name,
                //'value' => Mage::helper('connector')->getLogoImage($this->getRequest()->getParam('id')),
                'note' => 'Recommended size 640px x 180px. Allow .png file type'
            ));
        }

        $form->setValues($data);
        return parent::_prepareForm();
    }

}