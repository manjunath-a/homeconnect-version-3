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
class VDesign_Bookme_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Option
extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
{
	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('vdesign_bookme/edit/options/option.phtml');
	}
	/**
	 * Retrieve html templates for different types of product custom options
	 *
	 * @return string
	 */
	public function getTemplatesHtml()
	{
		$canEditPrice = $this->getCanEditPrice();
		$canReadPrice = $this->getCanReadPrice();
	
		$this->getChild('multidate_option_type')
		->setCanReadPrice($canReadPrice)
		->setCanEditPrice($canEditPrice);
		$templates = parent::getTemplatesHtml() . "\n" .
				$this->getChildHtml('multidate_option_type');
		return $templates;    
	} 

}
