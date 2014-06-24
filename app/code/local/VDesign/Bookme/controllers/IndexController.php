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
class VDesign_Bookme_IndexController extends Mage_Core_Controller_Front_Action
{
	
	public function indexAction(){
		
		$isAjax = Mage::app()->getRequest()->isAjax();
		if ($isAjax) {
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('outputHtml' => 'hello')));
		}
	}
	
	public function dayAction()
	{
		$year = $this->getRequest()->getParam('year');
		$month = $this->getRequest()->getParam('month');
		$product_id = $this->getRequest()->getParam('id');
		
		$helper = Mage::helper('bookme/availible');
		
		$out = $helper->getExcludeDays($product_id, $month - 1, $year);
		$out .= ','.$helper->getExcludeDays($product_id, $month, $year);
		$out .= ','.$helper->getExcludeDays($product_id, $month + 1, $year);
		
		$isAjax = Mage::app()->getRequest()->isAjax();
		if ($isAjax) {
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('outputHtml' => $out)));
		}
	}
	
	public function sessionAction(){
		$product_id = $this->getRequest()->getParam('id');
		$date = $this->getRequest()->getParam('date');
	
		$date = explode("/", $date);
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$date = (mktime(0,0,0,$date[1] + 1,$date[2],$date[0]) * 1000);
		
		$helper = Mage::helper('bookme/availible');
	
		$isAjax = Mage::app()->getRequest()->isAjax();
		if ($isAjax) {
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('outputHtml' => $helper->getSessions($product_id, $date))));
		}
	}
	
	public function maxqtyAction(){
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$helper = Mage::helper('bookme/availible');
		$maxqty = 0;
		$dates = $this->getRequest()->getParam('dates');
		$offset = $this->getRequest()->getParam('offset');
		
		$dates = ($dates != '')? substr($dates, 0, strlen($dates) - 1) : '';
		
		$dates = explode(",", $dates);
		$new_dates = '';
		 
		foreach ($dates as $date){
			date_default_timezone_set($offset);
			$client_date = date('Y-m-d H:i:s', $date / 1000);
			date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
			$new_dates = $new_dates.(strtotime($client_date) * 1000).',';
		}
		$dates = $new_dates;
		
		if($dates != ''){
			$dates = substr($dates, 0, strlen($dates) - 1);
			$product_id = $this->getRequest()->getParam('id');
			$product = Mage::getModel('catalog/product')->load($product_id);
			
			if(!$product->getId())
				$maxqty = 0;
			else
				$maxqty = $product->getData('bookable_qty');
			
			$date_array = explode(",", $dates);
			
			foreach ($date_array as $date){
				$qty = $helper->getAvailibleQty($product, date('Y-m-d H:i:s', $date / 1000));
				$maxqty = ($qty < $maxqty)? $qty : $maxqty;
			}
		} 
		$isAjax = Mage::app()->getRequest()->isAjax();
		if ($isAjax) {
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('outputHtml' => $maxqty)));
		}
	}
}