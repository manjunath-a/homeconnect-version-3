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

class VDesign_Bookme_Model_Product_Type_Booking
extends Mage_Catalog_Model_Product_Type_Simple{
	
	
	
	/**
	 * Check is virtual product
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @return bool
	 */
	public function isVirtual($product = null)
	{
		if($product)
		{
			$product = Mage::getModel('catalog/product')->load($product->getId());
			
			return $product->getAttributeText('include_shipping') == 'disabled';
		}
		
		return true;
	}
	
}