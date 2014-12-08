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
class VDesign_Bookme_Model_Catalog_Product_Option_Type_Multidate
    extends Mage_Catalog_Model_Product_Option_Type_Default
{
    public function isCustomizedView()
    {
        return true;
    }
 
    public function getCustomizedView($optionInfo)
    {
        $customizeBlock = new VDesign_Bookme_Block_Adminhtml_Options_Type_Customview_Multidate();
        $customizeBlock->setInfo($optionInfo);
        return $customizeBlock->toHtml();
    }
    
    public function getOptionPrice($optionValue, $basePrice)
    {
    	$option = $this->getOption();
    	
    	return (count(explode(",", $optionValue)) - 1) * $basePrice;
    	return $this->_getChargableOptionPrice(
    			$option->getPrice(),
    			$option->getPriceType() == 'percent',
    			$basePrice
    	);
    }
    
    public function validateUserValue($values)
    {
    	Mage::getSingleton('checkout/session')->setUseNotice(false);
    	
    	$this->setIsValid(false);
    	
    	$option = $this->getOption();
    	
    	$values[$option->getId()]['val'] = ($values[$option->getId()]['val'] != '')? substr($values[$option->getId()]['val'], 0, strlen($values[$option->getId()]['val']) - 1) : '';
    	
    	$dates = explode(",", $values[$option->getId()]['val']);
    	$offset = $values[$option->getId()]['offset'];
    	$values[$option->getId()] = '';
    	
    	foreach ($dates as $date){
    		date_default_timezone_set($offset);
    		$client_date = date('Y-m-d H:i:s', $date / 1000);
    		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
			$values[$option->getId()] .= (strtotime($client_date) * 1000).',';
    	}
    	
    	if (!isset($values[$option->getId()]) && $option->getIsRequire() && !$this->getSkipCheckRequiredOption()) {
    		Mage::throwException(Mage::helper('catalog')->__('Please specify the product required option(s).'));
    	} elseif (isset($values[$option->getId()])) {
    		$this->setUserValue($values[$option->getId()]);
    		$this->setIsValid(true);
    	}
    	return $this;
    }
}