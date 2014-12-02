<?php

class Simi_Connector_Model_Payment_Paypal extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'paypal_mobile';    
    protected $_infoBlockType = 'connector/payment_paypal';
	
	// public function getOrderPlaceRedirectUrl()
    // {
          // return Mage::getUrl('paypal/standard/redirect', array('_secure' => true));
    // }
}
