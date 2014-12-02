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
class Simi_Connector_Block_Adminhtml_Cms_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

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
        } elseif (Mage::registry('cms_data'))
            $data = Mage::registry('cms_data')->getData();
	
        $fieldset = $form->addFieldset('connector_form', array('legend' => Mage::helper('connector')->__('Block information')));
		$wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array(
                    'add_variables'		=> false,
                    'plugins'			=> array(),
                    'widget_window_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
                    'directives_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
                    'directives_url_quoted'	=> preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
                    'files_browser_window_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
            ));
       $fieldset->addField('website_id', 'select', array(
            'label' => Mage::helper('connector')->__('Choose website'),
            'name' => 'website_id',
            'values' => Mage::getSingleton('connector/status')->getWebsite(),
        ));

        $fieldset->addField('cms_title', 'text', array(
            'label' => Mage::helper('connector')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'cms_title',
        ));
		
		if (isset($data['cms_image']) && $data['cms_image']) {

            $data['cms_image'] = Mage::getBaseUrl('media') . 'simi/simicart/cms/' . $data['website_id'] . '/' . $data['cms_image'];
        }
				
		$fieldset->addField('cms_image', 'image', array(
            'label' => Mage::helper('connector')->__('Icon (width:64px, height:64px)'),            
            'name' => 'cms_image_o',
        ));
		
        $fieldset->addField('cms_content', 'editor', array(
            'name' => 'cms_content',
            'class' => 'required-entry',
            'required' => true,
			'config'	=> $wysiwygConfig,
            'label' => Mage::helper('connector')->__('Content'),
            'title' => Mage::helper('connector')->__('Content'),
			'style'		=> 'width: 600px;',
        ));
        

        $fieldset->addField('cms_status', 'select', array(
            'label' => Mage::helper('connector')->__('Enable'),
            'name' => 'cms_status',
            'values' => array(
				array('value' => 1, 'label' => Mage::helper('connector')->__('Yes')),
                array('value' => 0, 'label' => Mage::helper('connector')->__('No')),                
            )            
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }

}