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
 * @category 	Magestore
 * @package 	Magestore_Paypalmobile
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Paypalmobile Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Paypalmobile
 * @author  	Magestore Developer
 */
class Simi_Paypalmobile_Model_Paypalmobile extends Simi_Connector_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('paypalmobile/paypalmobile');
    }

    public function statusPending() {
        return array(
            'status' => 'PENDING',
        );
    }

    public function updatePaypalPayment($data) {
        $dataComfrim = $data;        
		// Zend_debug::dump($dataComfrim);die();
		
        if ($dataComfrim->payment_status == '2') {
            //update status order-> cancel			
            $this->setOrderCancel($dataComfrim->invoice_number);			
			return $this->statusError(array(Mage::helper('core')->__('The order has been cancelled')));
        }		
        //update version 0.2 api paypal
		$confirm_db = $dataComfrim->proof;
        $data = Mage::helper('paypalmobile')->getResponseBody($confirm_db, 0);
        $data['invoice_number'] = $dataComfrim->invoice_number;
        
        if ((isset($data['payment_status'] )&& $data['payment_status']== 'PENDING') 
				|| 
				(!isset($data['transaction_id']) || !$data['transaction_id'])
                || (!isset($data['invoice_number']) || !$data['invoice_number'])) {
            return $this->statusError(array($dataComfrim->payment_status));
        }
        try {
            if ($this->_initInvoice($data['invoice_number'], $data)){
				$informtaion = $this->statusSuccess();				
				$informtaion['message'] = array(Mage::helper('core')->__('Your order has been received.   Thank you for your purchase!'));
				return $informtaion;
			}            
            else{
				return $this->statusPending();
			}                				
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
        Mage::getModel('paypalmobile/paypalmobile')
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