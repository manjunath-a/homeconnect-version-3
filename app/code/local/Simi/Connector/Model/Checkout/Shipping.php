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
 * @category 	Simi
 * @package 	Simi_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Simi Model
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Checkout_Shipping extends Simi_Connector_Model_Checkout {

    public function saveShippingMethod($data) {
        $method_code = $data->s_method_code;
        try {
            $result = $this->_getOnepage()->saveShippingMethod($method_code);
            // Zend_debug::dump($result);die();
			$information = $this->statusSuccess();
            if (!$result) {                
                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => Mage::app()->getRequest(),
                    'quote' => $this->_getOnepage()->getQuote()));
                $this->_getOnepage()->getQuote()->collectTotals();                
            }
			$information['message'] = array(Mage::helper('core')->__('Save Success'));
            Mage::getSingleton('core/session')->setSimiShippingMethod($method_code);
            $this->_getOnepage()->getQuote()->collectTotals()->save();
            $total = $this->_getCheckoutSession()->getQuote()->getTotals();
            $tax = 0;
            if (isset($total['tax']) && $total['tax']->getValue()) {
                $tax = $total['tax']->getValue();
            } else {
                $tax = 0;
            }
            $discount = 0;
            if (isset($total['discount']) && $total['discount']) {
                $discount = abs($total['discount']->getValue());
            }
			 //coupon code (maybe)
			$coupon = '';
			if ($this->_getCheckoutSession()->getQuote()->getCouponCode())
				$coupon = $this->_getCheckoutSession()->getQuote()->getCouponCode();
            $total_data = array(
                'sub_total' => $total['subtotal']->getValue(),
                'grand_total' => $total['grand_total']->getValue(),
                'discount' => $discount,
                'tax' => $tax,
				'coupon_code' => $coupon,
            );
            $event_name = $this->getControllerName() . '_total';
            $event_value = array(
                'object' => $this,
            );
			 //hai ta 2082014
			$fee_v2 = array();
			Mage::helper('connector/checkout')->setTotal($total, $fee_v2);
			$total_data['v2'] = $fee_v2;
			//end haita 2082014
            $data_change = $this->changeData($total_data, $event_name, $event_value);
			
			$quote = $this->_getCheckoutSession()->getQuote();
			$totalPay = $quote->getBaseSubtotal() + $quote->getShippingAddress()->getBaseShippingAmount();
			$payment = Mage::getModel('connector/checkout_payment');
			Mage::dispatchEvent('simi_add_payment_method', array('object' => $payment));
			$paymentMethods = $payment->getMethods($quote, $totalPay);
			//list payment methods
			$list_payment = array();
			foreach ($paymentMethods as $method) {
				$list_payment[] = $payment->getDetailsPayment($method);
			}
			
            $information['data'] = array(array(
				'fee' => $data_change,
				'payment_method_list' => $list_payment,
				));
            return $information;
        } catch (Exception $e) {
            if (is_array($e->getMessage())) {
                $information = $this->statusError($e->getMessage());
            } else {
                $information = $this->statusError(array($e->getMessage()));
            }
            return $information;
        }
    }

	
	public function getAddress(){
		return $this->_getCheckoutSession()->getShippingAddress();
	}
		
	public function getShippingPrice($price, $flag)
    {
        return $this->_getCheckoutSession()->getQuote()->getStore()->convertPrice(Mage::helper('tax')->getShippingPrice($price, $flag, $this->getAddress()), false);
    }
	
	public function getMethods(){
		$shipping = $this->_getCheckoutSession()->getQuote()->getShippingAddress();
     
        $methods = $shipping->getGroupedAllShippingRates();
	
        //list shipping methods
        $list = array();
        foreach ($methods as $_ccode => $_carrier) {
            foreach ($_carrier as $_rate) {
				if($_rate->getData('error_message') != NULL){
					continue;
				}
				$select = false;
				if($shipping->getShippingMethod() != null && $shipping->getShippingMethod() == $_rate->getCode()){
					$select = true;
				}
				
				$s_fee = Mage::getModel('connector/checkout_shipping')->getShippingPrice($_rate->getPrice(), Mage::helper('tax')->displayShippingPriceIncludingTax());
				$s_fee_incl = Mage::getModel('connector/checkout_shipping')->getShippingPrice($_rate->getPrice(), true);
				
				if (Mage::helper('tax')->displayShippingBothPrices() && $s_fee != $s_fee_incl){
					$list[] = array(
						's_method_id' => $_rate->getId(),
						's_method_code' => $_rate->getCode(),
						's_method_title' => $_rate->getCarrierTitle(),
						's_method_fee' => Mage::app()->getStore()->convertPrice(floatval($s_fee), false),
						's_method_fee_incl_tax' => $s_fee_incl,
						's_method_name' => $_rate->getMethodTitle(),
						's_method_selected' => $select,
					);
				}else{
					 $list[] = array(
						's_method_id' => $_rate->getId(),
						's_method_code' => $_rate->getCode(),
						's_method_title' => $_rate->getCarrierTitle(),
						's_method_fee' => $s_fee,			
						's_method_name' => $_rate->getMethodTitle(),
						's_method_selected' => $select,
					);
				}
				
               
            }
        }
		$this->_getCheckoutSession()->getQuote()->collectTotals()->save();
		return $list;
	
	}
}