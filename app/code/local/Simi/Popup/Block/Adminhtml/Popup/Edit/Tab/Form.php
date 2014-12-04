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
 * @package 	Magestore_Popup
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Popup Edit Form Content Tab Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Popup
 * @author  	Magestore Developer
 */
class Simi_Popup_Block_Adminhtml_Popup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare tab form's information
     *
     * @return Simi_Popup_Block_Adminhtml_Popup_Edit_Tab_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getPopupData()) {
            $data = Mage::getSingleton('adminhtml/session')->getPopupData();
            Mage::getSingleton('adminhtml/session')->setPopupData(null);
        } elseif (Mage::registry('popup_data'))
            $data = Mage::registry('popup_data')->getData();
        $fieldset = $form->addFieldset('popup_form', array('legend' => Mage::helper('popup')->__('Popup information')));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array(
            'add_variables' => false,
            'plugins' => array(),
            'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
        ));


        $fieldset->addField('url', 'text', array(
            'label' => Mage::helper('popup')->__('Link download app'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'url',
        ));
		$data['content_show'] = $data['content'];
        $fieldset->addField('content_show', 'editor', array(
            'name' => 'content',
            'label' => Mage::helper('popup')->__('Content'),
            'title' => Mage::helper('popup')->__('Content'),
            'style' => 'width:500px; height:150px;',
            'wysiwyg' => true,
            'required' => true,
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
        ));
        $form->setValues($data);
        return parent::_prepareForm();
    }

}