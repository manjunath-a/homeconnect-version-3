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
 * Madapter Edit Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Simi_Connector_Block_Adminhtml_Notice_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'connector';
        $this->_controller = 'adminhtml_notice';

        $this->_updateButton('save', 'label', Mage::helper('connector')->__('Send'));
         $this->removeButton('delete');
        $this->removeButton('reset');
        $this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('notice_content') == null)
					tinyMCE.execCommand('mceAddControl', false, 'notice_content');
				else
					tinyMCE.execCommand('mceRemoveControl', false, 'notice_content');
			}

			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
    }

    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText() {
        if (Mage::registry('notice_data') && Mage::registry('notice_data')->getId())
            return Mage::helper('connector')->__("Edit Message '%s'", $this->htmlEscape(Mage::registry('notice_data')->getNoticeTitle()));
        return Mage::helper('connector')->__('Add Message');
    }

}