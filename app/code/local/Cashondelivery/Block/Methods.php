<?php

include 'Mage/checkout/Block/Onepage/payment/Methods';

class Hco_Cashondelivery_Block_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods {

    public function getCashOnDelivery() {
        $cashdeliverymessage = "no_message";
        $cashdeliverynomessage = "no_message";
        $cart = Mage::getSingleton('checkout/session')->getQuote();
        foreach ($cart->getAllItems() as $item) {
            $productId = $item->getProduct()->getId();
            $productDetails = Mage::getModel('catalog/product')->load($productId);
            if ($productDetails['cash_on_delivery'] == 1) {
                $cashondelivery = 'cashondelivery';
                $cashdeliverymessage = "showmessage";
            } else {
                $cashondelivery = 'No';
                $cashdeliverynomessage = "showmessage";
            }
        }
        $cashondeliveryinfo=array('cashondelivery'=>$cashondelivery,
                                  'cashdeliverymessage'=>$cashdeliverymessage,
                                  'cashdeliverynomessage'=>$cashdeliverynomessage);
        return $cashondeliveryinfo;
    }

}

?>
