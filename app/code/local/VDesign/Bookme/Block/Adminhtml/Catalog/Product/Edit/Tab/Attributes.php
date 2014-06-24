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

class VDesign_Bookme_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes
extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes{
	
	
	protected function _prepareForm(){
		
		parent::_prepareForm();
		$group = $this->getGroup();
		if ($group) {
			$form = $this->getForm();
			
			$attributes = Mage::getModel('bookme/attributes');
			
			foreach ($attributes->getAllOptions() as $code => $block)
			{
				$element = $form->getElement($code);
				if ($element) {
					$element->setRenderer(
							$this->getLayout()->createBlock($block)
					);
				}
			}
		}
		
	}
}