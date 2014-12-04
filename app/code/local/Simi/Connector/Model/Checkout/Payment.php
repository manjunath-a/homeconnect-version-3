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
class Simi_Connector_Model_Checkout_Payment extends Simi_Connector_Model_Checkout {

    //methods for payment
    public function _construct() {
        $this->_setListPayment();
        $this->setListCase();
    }

    protected function _getConfig() {
        return Mage::getSingleton('payment/config');
    }

    protected $_listPayment = array();
    protected $_listCase;

    protected function _setListPayment() {
        $this->_listPayment[] = 'zooz';
		$this->_listPayment[] = 'transfer_mobile';
		$this->_listPayment[] = 'cashondelivery';
		$this->_listPayment[] = 'checkmo';		
    }

    protected function _getListPayment() {
        return $this->_listPayment;
    }

    protected function _getListPaymentNoUse() {
        return array(
            'authorizenet_directpost'          
        );
    }

    public function setListCase() {
        $this->_listCase = array(
            'zooz' => 2,
            'transfer_mobile' => 0,
            'cashondelivery' => 0,
            'checkmo' => 0,			
        );
    }

    public function getListCase() {
        return $this->_listCase;
    }

    public function addMethod($method_code, $type) {
        $this->_listPayment[] = $method_code;		
        $this->_listPayment = array_unique($this->_listPayment);
        $this->_listCase[$method_code] = $type;			
    }

    public function savePaymentMethod($data) {
        $check_card = null;
        $method = null;
        if (isset($data->card_type) && $data->card_type) {
            if (isset($data->cc_id) && $data->cc_id) {
                $method = array('method' => strtolower($data->payment_method),
                    'cc_type' => $data->card_type,
                    'cc_number' => $data->card_number,
                    'cc_exp_month' => $data->expired_month,
                    'cc_exp_year' => $data->expired_year,
                    'cc_cid' => $data->cc_id,
                );
            } else {
                $method = array('method' => strtolower($data->payment_method),
                    'cc_type' => $data->card_type,
                    'cc_number' => $data->card_number,
                    'cc_exp_month' => $data->expired_month,
                    'cc_exp_year' => $data->expired_year,
                );
            }
            $check_card = $method;
        } else {
            $method = array('method' => strtolower($data->payment_method));
            $check_card = $method;
        }
        try {
            $address_cache = Mage::getSingleton('core/session')->getSimiAddress();
            $shipping_method = Mage::getSingleton('core/session')->getSimiShippingMethod();

            $this->_getOnepage()->saveCheckoutMethod($address_cache['checkout_method']);
            $this->_getOnepage()->saveBilling($address_cache['billing_address'], $address_cache['billing_address']['customer_address_id']);
            $this->_getOnepage()->saveShipping($address_cache['shipping_address'], $address_cache['shipping_address']['customer_address_id']);
            $this->_getCheckoutSession()->getQuote()->getShippingAddress()->collectShippingRates()->save();
            // save shipping	method
            $this->_getOnepage()->saveShippingMethod($shipping_method);
            $this->_getOnepage()->getQuote()->collectTotals()->save();
            //save payment method
            $this->_getOnepage()->savePayment($method);
        } catch (Exception $e) {
            $check_card = 'Exception';
            if (is_array($e->getMessage())) {
                Mage::getSingleton('core/session')->setErrorPayment($e->getMessage());
            } else {
                Mage::getSingleton('core/session')->setErrorPayment(array($e->getMessage()));
            }
        }
        return $check_card;
    }

    public function getMethods($quote, $total) {
        $methods = $this->getData('methods');
        if (is_null($methods)) {
            $store = $quote ? $quote->getStoreId() : null;
            $methods = Mage::helper('payment')->getStoreMethods($store, $quote);		
		// Zend_debug::dump($methods);die();				
            foreach ($methods as $key => $method) {			
                if ($this->_canUseMethod($method, $quote)
                        && (!in_array($method->getCode(), $this->_getListPaymentNoUse()) &&
                        (in_array($method->getCode(), $this->_getListPayment()) || $method->getConfigData('cctypes')))
                        && ($total != 0
                        || $method->getCode() == 'free'
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles()))) {
                    $this->_assignMethod($method, $quote);
                } else {
                    unset($methods[$key]);
                }
            }
			
            $this->setData('methods', $methods);
        }
        return $methods;
    }

    protected function _assignMethod($method, $quote) {
        $method->setInfoInstance($quote->getPayment());
        return $this;
    }

    protected function _canUseMethod($method, $quote) {
        if (!$method->canUseForCountry($quote->getBillingAddress()->getCountry())) {
            return false;
        }

        if (!$method->canUseForCurrency($quote->getStore()->getBaseCurrencyCode())) {
            return false;
        }

        /**
         * Checking for min/max order total for assigned payment method
         */
        $total = $quote->getBaseGrandTotal();
        $minTotal = $method->getConfigData('min_order_total');
        $maxTotal = $method->getConfigData('max_order_total');

        if ((!empty($minTotal) && ($total < $minTotal)) || (!empty($maxTotal) && ($total > $maxTotal))) {
            return false;
        }
        return true;
    }

    public function getCcAvailableTypes($method) {
        $types = $this->_getConfig()->getCcTypes();
        $availableTypes = $method->getConfigData('cctypes');
        $cc_types = array();
        if ($availableTypes) {
            $availableTypes = explode(',', $availableTypes);
            foreach ($types as $code => $name) {
                if (!in_array($code, $availableTypes)) {
                    unset($types[$code]);
                } else {
                    $cc_types[] = array(
                        'cc_code' => $code,
                        'cc_name' => $name,
                    );
                }
            }
        }
        return $cc_types;
    }

    //return array
    public function getDetailsPayment($method) {
        $code = $method->getCode();
        $list = $this->getListCase();
		
		
		$type = 1;
        if (in_array($code, $this->_getListPayment())) {
            $type = $list[$code];			
        }
	
        $detail = array();
        if ($type == 0) {
            if ($code == "checkmo") {
                $detail['payment_method'] = strtoupper($method->getCode());
                $detail['title'] = $method->getConfigData('title');
                $detail['content'] = Mage::helper('connector')->__('Make Check Payable to: ') . $method->getConfigData('payable_to') . Mage::helper('connector')->__('Send Check to: ') . $method->getConfigData('mailing_address');
                $detail['show_type'] = 0;
            } else {
                $detail['content'] = $method->getConfigData('instructions');
                $detail['payment_method'] = strtoupper($method->getCode());
                $detail['title'] = $method->getConfigData('title');
                $detail['show_type'] = 0;
            }
        } elseif ($type == 1) {
            $detail['cc_types'] = $this->getCcAvailableTypes($method);
            $detail['payment_method'] = strtoupper($method->getCode());
            $detail['title'] = $method->getConfigData('title');
            $detail['useccv'] = $method->getConfigData('useccv');
            $detail['show_type'] = 1;
        } elseif ($type == 2) {			
            $detail['email'] = $method->getConfigData('business_account');
            $detail['client_id'] = $method->getConfigData('client_id');
            $detail['is_sandbox'] = $method->getConfigData('is_sandbox');
            $detail['payment_method'] = strtoupper($method->getCode());
            $detail['title'] = $method->getConfigData('title');
			$detail['bncode'] = "Magestore_SI_MagentoCE";
            $detail['show_type'] = 2;
        } elseif ($type == 3){
			$detail['payment_method'] = strtoupper($method->getCode());
            $detail['title'] = $method->getConfigData('title');			            
            $detail['show_type'] = 3;				
		}
		
        return $detail;
    }

    //end for payment
    public function statusPending() {
        return array(
            'status' => 'PENDING',
        );
    }

    public function updatePaypalPayment($data) {
        $dataComfrim = $data;
        $confirm_db = $dataComfrim->paypal_confirm;
        if ($dataComfrim->payment_status == "CANCELLED") {
            //update status order-> cancel
            $this->setOrderCancel($dataComfrim->invoice_number);
        }
//        $data = array();
        //update version 0.2 api paypal
        $data = Mage::helper('connector/paypal')->getResponseBody($confirm_db, 0);
        $data['invoice_number'] = $dataComfrim->invoice_number;
//        if (is_object($confirm_db->proof_of_payment->adaptive_payment)) {
//            $data = Mage::helper('connector/paypal')->getResponseBody($confirm_db->proof_of_payment->adaptive_payment, 1);
//            $data['invoice_number'] = $dataComfrim->invoice_number;
//        } else {
//            $data = Mage::helper('connector/paypal')->getResponseBody($confirm_db->proof_of_payment, 0);
//            $data['invoice_number'] = $dataComfrim->invoice_number;
//        }

        if (!(isset($data['payment_status'])
                || !(isset($data['transaction_id'])))
                || !(isset($data['invoice_number']))) {
            return $this->statusError($data);
        }
        if ($data['payment_status'] == 'PENDING' || !$data['transaction_id']
                || !$data['invoice_number']) {
            return $this->statusError();
        }
        try {
            if ($this->_initInvoice($data['invoice_number'], $data))
                return $this->statusSuccess();
            else
                return $this->statusPending();
        } catch (Exception $e) {
            if (is_array($e->getMessage())) {
                return $this->statusError($e->getMessage());
            } else {
                return $this->statusError(array($e->getMessage()));
            }
        }
    }

    protected function _initInvoice($orderId, $data) {
        $items = array();
        $order = $this->_getOrder($orderId);
        if (!$order)
            return false;
        foreach ($order->getAllItems() as $item) {
            $items[$item->getId()] = $item->getQtyOrdered();
        }

        //Zend_debug::dump(get_class_methods($order));die();
        Mage::getModel('connector/payment')
                ->setData('transaction_id', $data['transaction_id'])
                ->setData('transaction_name', $data['fund_source_type'])
                ->setData('transaction_dis', $data['last_four_digits'])
                ->setData('transaction_email', $data['transaction_email'])
                ->setData('amount', $data['amount'])
                ->setData('currency_code', $data['currency_code'])
                ->setData('status', $data['payment_status'])
                ->setData('order_id', $order->getId())
                ->save();
        Mage::getSingleton('core/session')->setOrderIdForEmail($order->getId());
        /* @var $invoice Mage_Sales_Model_Service_Order */
        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($items);
        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
        $invoice->setEmailSent(true)->register();
        //$invoice->setTransactionId();
        Mage::register('current_invoice', $invoice);
        $invoice->getOrder()->setIsInProcess(true);
        $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());
        $transactionSave->save();
        //if ($data)
        //$order->sendOrderUpdateEmail();
        $order->sendNewOrderEmail();
        Mage::getSingleton('core/session')->setOrderIdForEmail(null);
        return true;
    }

    protected $_order;

    protected function _getOrder($orderId) {
        if (is_null($this->_order)) {
            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            if (!$this->_order->getId()) {
                throw new Mage_Payment_Model_Info_Exception(Mage::helper('core')->__("Can not create invoice. Order was not found."));
                return;
            }
        }
        if (!$this->_order->canInvoice())
            return FALSE;
        return $this->_order;
    }

    protected function setOrderCancel($orderIncrementId) {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)->save();
    }

}

