<?php

/**
 * One page checkout processing model
 */

class FME_Bookingreservation_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    
    /**
     * Validate quote state to be able submited from one page checkout page
     *
     * @deprecated after 1.4 - service model doing quote validation
     * @return Mage_Checkout_Model_Type_Onepage
     
     
      Remove validation against Shipping, Method (for 'home service and service products')
     */
    
    
    
    protected function validateOrder()
    {
        if ($this->getQuote()->getIsMultiShipping()) {
            Mage::throwException(Mage::helper('checkout')->__('Invalid checkout type.'));
        }
        
        
                                        
                    $allow_shipping_steps = false; // if atleast one non service(physical) product is added, then add shipping steps                   
                    $dropdown_option_label = '';
                    
                    $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();        
                    
                    foreach($items as $iteminfo){         
                        
                        $product = Mage::getModel('catalog/product')->load($iteminfo->getProduct()->getId());                        
                        
                        if(!$product->getIsBookingProduct()){
                            
                            $allow_shipping_steps = true;
                            
                        }
                        
                        
                        
                        
                        //if($iteminfo->getParentItemId() == '' || $iteminfo->getParentItemId() == null){
                        //
                        //        $dropdownAttributeObj = $product->getResource()->getAttribute("booking_type");
                        //        
                        //        if ($dropdownAttributeObj->usesSource()) {
                        //              
                        //              $dropdown_option_label = $dropdownAttributeObj->getSource()->getOptionText($product->getData('booking_type'));
                        //        }
                        //        
                        //        
                        //        if($dropdown_option_label == "Mobile Booking"){
                        //            
                        //            $allow_shipping_steps = false;                                    
                        //            
                        //        }elseif($dropdown_option_label == "Service Booking"){
                        //            
                        //            $allow_shipping_steps = false;
                        //        }
                        //        else{
                        //            $allow_shipping_steps = true;
                        //        }
                        //
                        //}
                    } 
        
        
        
        
        
        if (!$this->getQuote()->isVirtual() && $allow_shipping_steps == true) {
            
            $address = $this->getQuote()->getShippingAddress();
            $addressValidation = $address->validate();
            if ($addressValidation !== true) {
                Mage::throwException(Mage::helper('checkout')->__('Please check shipping address information.'));
            }
            $method= $address->getShippingMethod();
            $rate  = $address->getShippingRateByCode($method);
            if (!$this->getQuote()->isVirtual() && (!$method || !$rate)) {
                Mage::throwException(Mage::helper('checkout')->__('Please specify shipping method.'));
            }
        }

        $addressValidation = $this->getQuote()->getBillingAddress()->validate();
        if ($addressValidation !== true) {
            Mage::throwException(Mage::helper('checkout')->__('Please check billing address information.'));
        }

        if (!($this->getQuote()->getPayment()->getMethod())) {
            Mage::throwException(Mage::helper('checkout')->__('Please select valid payment method.'));
        }
    }



}
