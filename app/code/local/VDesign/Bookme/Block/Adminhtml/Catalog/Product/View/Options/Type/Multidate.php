<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to vdesign.support@outlook.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @copyright  Copyright (c) 2014 VDesign
 * @license    End User License Agreement (EULA)
 */
class VDesign_Bookme_Block_Adminhtml_Catalog_Product_View_Options_Type_Multidate
extends Mage_Catalog_Block_Product_View_Options_Abstract
{
 
	protected function _construct()
	{
		parent::_construct();
	}
	
	/**
	 * Returns default value to show in text input
	 *
	 * @return string
	 */
	public function getDefaultValue()
	{
		return $this->getProduct()->getPreconfiguredValues()->getData('options/' . $this->getOption()->getId());
	}
	
	public function getBookPrice(){
		return $this->getProduct()->getPrice();
	}
	
	public function getSessions(){
		return '[]';
	}

}