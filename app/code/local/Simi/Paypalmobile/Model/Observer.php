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
 * Paypalmobile Observer Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Paypalmobile
 * @author  	Magestore Developer
 */
class Simi_Paypalmobile_Model_Observer {

    /**
     * process controller_action_predispatch event
     *
     * @return Simi_Paypalmobile_Model_Observer
     */
    public function addPayment($observer) {
        $object = $observer->getObject();		
		$object->addMethod('paypal_mobile', 2);		
		return;
    }

    public function paymentMethodIsActive($observer) {
        $result = $observer['result'];
        $method = $observer['method_instance'];
        //$store = $quote ? $quote->getStoreId() : null;            
        if ($method->getCode() == 'paypal_mobile') {
             if (Mage::app()->getRequest()->getControllerModule() != 'Simi_Connector' 
				&& Mage::app()->getRequest()->getControllerModule() != 'Simi_Hideaddress') {
                $result->isAvailable = false;
            }
        }
    }

}