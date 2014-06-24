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
class VDesign_Bookme_Model_Product_Attribute_Backend_Pricerule
extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract{
	
	/**
	 * Retrieve resource instance
	 *
	 */
	protected function _getResource()
	{
		return Mage::getResourceSingleton('bookme/product_attribute_backend_pricerule');
	}
	
	public function afterSave($object)
	{
	
		$priceRows = $object->getData($this->getAttribute()->getName());
		$types = array();
		foreach ($priceRows as $row){
			$rule = Mage::getModel('bookme/book_pricerule');
				
			if(isset($row['rule_id'])){
				$rule = $rule->load($row['rule_id']);
				if(!$rule->getId()){
					unset($row['rule_id']);
					$rule = Mage::getModel('bookme/book_pricerule');
				}else{
					if($row['deleted'] == 1){
						$rule->delete();
						continue;
					}
				}
			}
			if($row['deleted'] == 1){
				continue;
			}
			
			$rule->setProduct($object);
			$rule->setType($row['type']);
			if($row['qty'] == null)
				Mage::throwException(Mage::helper('bookme')->__("Please specify a quantity in price rule."));
			$rule->setValue($row['qty']);
			if(isset($row['qtytype']))
				$rule->setValueType($row['qtytype']);
			$rule->setMove($row['move']);
			$rule->setAmountType($row['amount_type']);
			$rule->setAmount($row['amount']);
			if($row['amount'] == null)
				Mage::throwException(Mage::helper('bookme')->__("Please specify an amount in price rule."));

			if(!isset($types[$row['type']]))
				$types[$row['type']] = true;
			else
				Mage::throwException(Mage::helper('bookme')->__("Product can't have more price rules of the same type."));
			
			$rule->save();
		}
	
		return $this;
	}
	
	public function afterLoad($object){
	
		$data = array();
		$collection = Mage::getModel('bookme/book_pricerule')->getCollection()->addFilter('entity_id', $object->getId());
		foreach ($collection as $item){
			$pom = array();
			foreach ($item->getData() as $key => $value){
				if($value)
					$pom[$key] = $value;
			}
			$data[] = $pom;
		}
		$object->setData($this->getAttribute()->getAttributeCode(), $data);
		$object->setOrigData($this->getAttribute()->getAttributeCode(), $data);
	
		return $this;
	}
}