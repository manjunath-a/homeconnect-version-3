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
class VDesign_Bookme_Model_Observer extends Mage_Core_Model_Observer{

	
	/**
	 * Retrieve the product model
	 *
	 * @return Mage_Catalog_Model_Product $product
	 */
	public function getProduct()
	{
		return Mage::registry('product');
	}
	 
	/**
	 * Shortcut to getRequest
	 *
	 */
	protected function _getRequest()
	{
		return Mage::app()->getRequest();
	}
	
	
	public function validateBookingInOrder(Varien_Event_Observer $observer){
		
		$order = $observer->getEvent()->getOrder();
		
		if(!$order->getId())
		{
			if(!$this->validateBookOptions($order))
				Mage::throwException(Mage::helper('bookme')->__('Can`t be booked'));
			else return $this;
		}
		return $this;
	}
	
	public function cleanCanceledBooks(Varien_Event_Observer $observer){
		
		$item = $observer->getEvent()->getItem();
		if($item->getProduct()->getTypeId() == 'booking')
		{
			$book_item = Mage::getModel('bookme/book_item')->load($item->getId(), 'order_item_id');
			if($book_item->getId())
				$book_item->delete();
		}
		return true;
	}
	
	public function cleanCreditMemoBooks(Varien_Event_Observer $observer){
		$creditmemo = $observer->getEvent()->getCreditmemo();
		
		foreach ($creditmemo->getAllItems() as $item) {
			
			if($item->getOrderItem()->getProduct()->getTypeId() == 'booking')
			{
				$book_item = Mage::getModel('bookme/book_item')->load($item->getOrderItem()->getId(), 'order_item_id');
				if($book_item->getId())
					$book_item->delete();
			}
		}
		return true;
	}
	
	public function validateBookOptions($order){
		
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		
		$helper = Mage::helper('bookme/availible');
		$qtys = array();
		foreach ($order->getAllItems() as $item){
			if($item->getProduct()->getTypeId() == 'booking'){
				$poptions = $item->getProductOptions();
				$options = array();
				if(is_array($poptions) && isset($poptions['options']))
					$options = $poptions['options'];
				else{
					return true;
				}
				
				foreach ($options as $option){
					if($option['option_type'] == 'multidate_type'){
						
						$values = explode(",", $option['value']);
						
						if(!(count($values) > 1 && is_numeric($values[0])))
							Mage::throwException(Mage::helper('bookme')->__('Booking data is in wrong format.'));
						
						$dateFrom = date('Y-m-d H:i:s', $values[0] / 1000);
						
						if(count($values) > 1)
							$dateTo = date('Y-m-d H:i:s', $values[count($values) - 2] / 1000);
						else
							$dateTo = date('Y-m-d H:i:s', $values[0] / 1000);
						
						$timeFrom = strtotime($dateFrom);
						$timeTo = strtotime($dateTo);
						$qtys[$item->getProduct()->getId()] = (isset($qtys[$item->getProduct()->getId()]))? $qtys[$item->getProduct()->getId()] : array();
						for($i = 0; $i < count($values) - 1; $i++){
							$checkingDate = date('Y-m-d H:i:s', $values[$i] / 1000);
							
							if(isset($qtys[$item->getProduct()->getId()][$checkingDate]))
								$qtys[$item->getProduct()->getId()][$checkingDate] += $item->getQtyOrdered();
							else
								$qtys[$item->getProduct()->getId()][$checkingDate] = $item->getQtyOrdered();
							$timeFrom += 24 * 60 * 60;
						}
						
					}
				}
			}
		}
		
		foreach ($qtys as $product_id => $values){
			foreach ($values as $day => $qty){
				if($helper->isAvailible($product_id, $day, $qty) === false){
					$product = Mage::getModel('catalog/product')->load($product_id);
					Mage::throwException(Mage::helper('bookme')->__($product->getName(). ' can`t be book in that quantity.'));
				}	
			}	
		}
		
		return true;
	}
	
	public function saveBookOptions(Varien_Event_Observer $observer){
		
		$order = $observer->getEvent()->getOrder();
		
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$dateFrom = '';
		$dateTo   = '';
		try{
			$book = Mage::getModel('bookme/book');
			$book->setOrderId($order->getId());
			$book->setCustomerFirstname($order->getCustomerFirstname());
			$book->setCustomerLastname($order->getCustomerLastname());
			$book->setCustomerEmail($order->getCustomerEmail());
			$book->setCustomerPhone($order->getBillingAddress()->getData('telephone'));
			$book->save();
			
			foreach ($order->getAllItems() as $item){
				if($item->getProduct()->getTypeId() == 'booking'){
					
					$product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
					
					$poptions = $item->getProductOptions();
					$options = array();
					if(is_array($poptions) && isset($poptions['options']))
						$options = $poptions['options'];
					else{
						return true;
					}
			
					foreach ($options as $option){
						if($option['option_type'] == 'multidate_type'){
							$values = explode(",", $option['value']);
							
							if($product->getAttributeText('billable_period') == 'Session'){
								for($i = 0; $i < count($values) - 1; $i++){
									$dateFrom = date('Y-m-d H:i:s', $values[$i] / 1000);
									$dateTo = $dateFrom;
									
									$this->saveBookItem($book->getId(), $product->getId(), $item->getId(), $dateFrom, $dateTo, $item->getQtyOrdered());
								}
								break;
							}else{
								$dateFrom = date('Y-m-d H:i:s', $values[0] / 1000);
								$dateTo = (count($values) > 1)? date('Y-m-d H:i:s', $values[count($values) - 2] / 1000) : $dateFrom;
									
								$this->saveBookItem($book->getId(), $product->getId(), $item->getId(), $dateFrom, $dateTo, $item->getQtyOrdered());
								break;
							}
						}
					}
				}
			}
			
		}catch (Exception $e){
			Mage::logException($e);
			Mage::throwException(Mage::helper('adminhtml')->__($e));
		}
		return true;
		
	}
	
	//product_id is referencing to order item id
	public function saveBookItem($book_id, $product_id, $item_id, $dateFrom, $dateTo, $qty){
		$book_item = Mage::getModel('bookme/book_item');
		$book_item->setBookId($book_id);
		$book_item->setProductId($product_id);
		$book_item->setOrderItemId($item_id);
		$book_item->setBookedFrom($dateFrom);
		$book_item->setBookedTo($dateTo);
		$book_item->setQty($qty);
		$book_item->save();
	}

	
	public function checkCartAdd($observer) {
		if($observer->getProduct()->getTypeId() == 'booking')
		{
	        $max = Mage::app()->getRequest()->getParam('pikaday_max_qty');
	        $qty = Mage::app()->getRequest()->getParam('qty');
	        
	        if($max < $qty){
	        	Mage::throwException(Mage::helper('bookme')->__('Such quantity to book is not availible.'));
	        }
		}
        return $this;
	}
	
	public function duplicateProduct($observer)
	{
		$current_product = $observer->getCurrentProduct();
		$new_product = $observer->getNewProduct();
		
		$sessions = $current_product->getData('custom_session');
		
		for ($i = 0; $i < count($sessions); $i++)
		{
			unset($sessions[$i]['session_id']);
			for ($j = 0; $j < count($sessions[$i]['sessions']); $j++)
			{
				unset($sessions[$i]['sessions'][$j]['session_id']);
			}
		}
		
		$new_product->setData('custom_session', $sessions);
		
		$exclude_days = $current_product->getData('exclude_day');
		
		for($i = 0; $i < count($exclude_days); $i++)
		{
			unset($exclude_days[$i]['entity_id']);
			unset($exclude_days[$i]['exday_id']);
		}
		
		$new_product->setData('exclude_day', $exclude_days);
		
		
		$price_rules = $new_product->getData('price_rule');
		for($i = 0; $i < count($price_rules); $i++)
		{
			unset($price_rules[$i]['rule_id']);
			unset($price_rules[$i]['entity_id']);
			$price_rules[$i]['qty'] = $price_rules[$i]['value'];
			$price_rules[$i]['qtytype'] = $price_rules[$i]['value_type'];
		}
		
		$new_product->setData('price_rule', $price_rules);
		
		$observer->setNewProduct($new_product);
		return $observer;
	}
	
}