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
 * Madapter Edit Tabs Block
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Block_Adminhtml_Notice_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct(){
		parent::__construct();
		$this->setId('notice_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('connector')->__('Notification Information'));
	}
	
	/**
	 * prepare before render block to html
	 *
	 * @return Magestore_Madapter_Block_Adminhtml_Madapter_Edit_Tabs
	 */
	protected function _beforeToHtml(){
		$this->addTab('form_section', array(
			'label'	 => Mage::helper('connector')->__('Notification Information'),
			'title'	 => Mage::helper('connector')->__('Notification Information'),
			'content'	 => $this->getLayout()->createBlock('connector/adminhtml_notice_edit_tab_form')->toHtml(),
		));
		return parent::_beforeToHtml();
	}
}