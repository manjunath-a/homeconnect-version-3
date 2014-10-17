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
class Simi_Connector_Block_Adminhtml_Notice_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare tab form's information
     *
     * @return Simi_Connector_Block_Adminhtml_Banner_Edit_Tab_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $websites = Mage::helper('connector')->getWebsites();
        $list_web = array();
        foreach ($websites as $website) {
            $list_web[] = array(
                'value' => $website->getId(),
                'label' => $website->getName(),
            );
        }
        if (Mage::getSingleton('adminhtml/session')->getConnectorData()) {
            $data = Mage::getSingleton('adminhtml/session')->getConnectorData();
            Mage::getSingleton('adminhtml/session')->setConnectorData(null);
        } elseif (Mage::registry('notice_data'))
            $data = Mage::registry('notice_data')->getData();

        $fieldset = $form->addFieldset('connector_form', array('legend' => Mage::helper('connector')->__('Notification information')));

        $fieldset->addField('website_id', 'select', array(
            'label' => Mage::helper('connector')->__('Choose website'),
            'name' => 'website_id',
            'values' => $list_web,
        ));

        $fieldset->addField('notice_title', 'text', array(
            'label' => Mage::helper('connector')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'notice_title',
        ));

        $fieldset->addField('notice_url', 'text', array(
            'name' => 'notice_url',
            'class' => 'required-entry',
            'required' => true,
            'label' => Mage::helper('connector')->__('URL'),
            'title' => Mage::helper('connector')->__('URL'),
        ));

        $fieldset->addField('notice_content', 'editor', array(
            'name' => 'notice_content',
            'class' => 'required-entry',
            'required' => true,
            'label' => Mage::helper('connector')->__('Message'),
            'title' => Mage::helper('connector')->__('Message'),
        ));
//        Zend_debug::dump(Mage::getSingleton('connector/status')->getOptionHash());die();        

        $fieldset->addField('device_id', 'select', array(
            'label' => Mage::helper('connector')->__('Choose OS'),
            'name' => 'device_id',
            'values' => array(
                array('value' => 0, 'label' => Mage::helper('connector')->__('All')),
                array('value' => 1, 'label' => Mage::helper('connector')->__('IOS')),
                array('value' => 2, 'label' => Mage::helper('connector')->__('Android')),
            ),
            'onchange' => 'onchangeDevice()',
        ));

        // $fieldset->addField('notice_sanbox', 'select', array(
            // 'label' => Mage::helper('connector')->__('Is it in Sandbox mode?'),
            // 'name' => 'notice_sanbox',
            // 'values' => array(
                // array('value' => 0, 'label' => Mage::helper('connector')->__('No')),
                // array('value' => 1, 'label' => Mage::helper('connector')->__('Yes')),
            // ),
            // 'note' => 'used for IOS',
            // 'after_element_html' => ' <script type="text/javascript">                    
                    // function onchangeDevice(){                    
                         // var check = $(\'device_id\').value;                         
                         // if(check == 2)                          
                           // $(\'notice_sanbox\').parentNode.parentNode.hide();      
                         // else
                            // $(\'notice_sanbox\').parentNode.parentNode.show();    
                    // }                                               
                        // </script>',
        // ));


        $form->setValues($data);
        return parent::_prepareForm();
    }

}