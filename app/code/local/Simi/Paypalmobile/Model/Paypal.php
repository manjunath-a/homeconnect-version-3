<?php

class Simi_Paypalmobile_Model_Paypal extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'paypal_mobile';    
    protected $_infoBlockType = 'paypalmobile/paypal';
	
	// public function getOrderPlaceRedirectUrl()
    // {
          // return Mage::getUrl('paypal/standard/redirect', array('_secure' => true));
    // }
}
