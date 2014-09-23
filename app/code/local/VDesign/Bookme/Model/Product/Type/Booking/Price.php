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
class VDesign_Bookme_Model_Product_Type_Booking_Price
extends Mage_Catalog_Model_Product_Type_Price
{
	/**
	 * Multidate Group Instance
	 *
	 * @var VDesign_Bookme_Model_Catalog_Product_Option_Type_Multidate
	 */
	
	protected $_mgroup;
	
	/*
	 * Option Value of Multidate
	 */
	protected $_optionValue;
	

//     public function getFinalPrice($qty = null, $product)
//     {
//         if (is_null($qty) && !is_null($product->getCalculatedFinalPrice())) {
//             return $product->getCalculatedFinalPrice();
//         }

//         $finalPrice = $this->getBasePrice($product, $qty);
//         $product->setFinalPrice($finalPrice);

//         Mage::dispatchEvent('catalog_product_get_final_price', array('product' => $product, 'qty' => $qty));

//         $finalPrice = $product->getData('final_price');
//         $finalPrice = $this->_applyOptionsPrice($product, $qty, $finalPrice);
//         $finalPrice = max(0, $finalPrice);
//         $product->setFinalPrice($finalPrice);

//         return $finalPrice;
//     }
    

	
	protected function _applyOptionsPrice($product, $qty, $finalPrice)
	{
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		if ($optionIds = $product->getCustomOption('option_ids')) {
			$basePrice = $finalPrice;
			
			foreach (explode(',', $optionIds->getValue()) as $optionId) {
				if ($option = $product->getOptionById($optionId)) {
					$confItemOption = $product->getCustomOption('option_'.$option->getId());
	
					$group = $option->groupFactory($option->getType())
					->setOption($option)
					->setConfigurationItemOption($confItemOption);
					
					if($group instanceof VDesign_Bookme_Model_Catalog_Product_Option_Type_Multidate)
					{
						$group->setProduct($product);
						$this->_mgroup = $group;
						$this->_optionValue = $confItemOption->getValue();
						
						$data = explode(',', $confItemOption->getValue());
						
						$p = Mage::getModel('catalog/product')->load($product->getId());
						
						$discount1 = $this->applyFirstMoment($p, $data[0] / 1000, $finalPrice);
						$discount2 = $this->applyLastMinute($p, $data[count($data) - 2] / 1000, $finalPrice);
						$discount3 = $this->applyPeriodQty($p, count($data) - 1, $finalPrice);
						
						$finalPrice = $finalPrice - $discount1 - $discount2 - $discount3;
					}
					else
						$finalPrice += $group->getOptionPrice($confItemOption->getValue(), $basePrice);
				}
			}
			$finalPrice = $this->_mgroup->getOptionPrice($this->_optionValue, $finalPrice);
			
		}
	
		return $finalPrice;
	}
	
	
	public function applyFirstMoment($product, $data, $price){
		$rules = $product->getData('price_rule');
		
		foreach ($rules as $rule){
			if($rule['type'] == 1){
				$diff = $this->getDifferention($rule);
				if(strtotime(date('Y-m-d')) + $diff <= $data){
					return $this->applyRule($rule, $price);
				}
			}
		}
		return 0;
	}
	
	public function applyLastMinute($product, $data, $price){
		
		$rules = $product->getData('price_rule');
		foreach ($rules as $rule){
			if($rule['type'] == 2){
				$diff = $this->getDifferention($rule);
				if(strtotime(date('Y-m-d')) + $diff >= $data){
					return $this->applyRule($rule, $price);
				}
			}
		}
		return 0;
	}
	
	public function applyPeriodQty($product, $data, $price){
		$rules = $product->getData('price_rule');
		foreach ($rules as $rule){
			if($rule['type'] == 3){
				
				if($rule['value'] <= $data){
					return $this->applyRule($rule, $price);
				}
			}
		}
		return 0;
	}
	
	public function applyRule($rule, $price)
	{
		if($rule['move'] == "1"){
			if($rule['amount_type'] == "1")
				return 0 - ($price * ($rule['amount'] / 100));
			else
				return (0 - $rule['amount']);
		}else{
			if($rule['amount_type'] == "1")
				return $price * ($rule['amount'] / 100);
			else
				return $rule['amount'];
		}
	}
	
	
	
	
	public static function getDifferention($rule){
		switch ($rule['value_type']){
			case '1' : return $rule['value'] * 24 * 60 * 60; //days
			case '2' : return $rule['value'] * 7 * 24 * 60 * 60; //weeks
			case '3' : return $rule['value'] * 30 * 24 * 60 * 60; //months
		}
	}

}