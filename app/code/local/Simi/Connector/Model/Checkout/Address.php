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
class Simi_Connector_Model_Checkout_Address extends Simi_Connector_Model_Checkout {
		
	 
     public function getAddressBilling()
	 {        
            if ($this->customerLogin()) {
                return $this->_getCheckoutSession()->getQuote()->getBillingAddress();
                
            } else {
                return  Mage::getModel('sales/quote_address');
            }        		
    }
	
	public function getAddressShipping()
	 {        
            if ($this->customerLogin()) {
                return $this->_getCheckoutSession()->getQuote()->getShippingAddress();
                
            } else {
                return Mage::getModel('sales/quote_address');
            }        		
    }
}