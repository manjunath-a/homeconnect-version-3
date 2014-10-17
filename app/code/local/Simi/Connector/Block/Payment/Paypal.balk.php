<?php

class Simi_Connector_Block_Payment_Paypal extends Mage_Payment_Block_Info_Cc {

    protected $_tranS;
    

    protected function _prepareSpecificInformation($transport = null) {
        $orderId = Mage::app()->getRequest()->getParam('order_id');
        $invoiceId = Mage::app()->getRequest()->getParam('invoice_id');
        if ($invoiceId) {
            $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId);
            $orderId = $invoice->getOrderId();
        } elseif (Mage::getSingleton('core/session')->getOrderIdForEmail()) {
            $orderId = Mage::getSingleton('core/session')->getOrderIdForEmail();
        }
        $train = Mage::getModel('connector/payment')->getCollection()
                ->addFieldToFilter('order_id', $orderId)
                ->getLastItem();
        $this->_tranS = $train;
        $info = null;
        $transport = parent::_prepareSpecificInformation($transport);
        if (count($train->getData())) {
            $info = array('Transaction ID' => $train->getTransactionId(),
                'Fund Source Type' => $train->getTransactionName()
                    );
        } else {
            $info = array('Notice' => 'Pending');
        }

        return $transport->addData($info);
    }

    public function getCcTypeName() {
        return '';
    }

}
