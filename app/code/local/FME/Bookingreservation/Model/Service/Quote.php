<?php
/**
 * Quote submit service model
 */

class FME_Bookingreservation_Model_Service_Quote extends Mage_Sales_Model_Service_Quote
{
  

    /**
     * Validate quote data before converting to order
     *
     * @return Mage_Sales_Model_Service_Quote
     */
    protected function _validate()
    {        
        
                                       
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
                        //    $dropdownAttributeObj = $product->getResource()->getAttribute("booking_type");
                        //    
                        //    if ($dropdownAttributeObj->usesSource()) {
                        //          
                        //        $dropdown_option_label = $dropdownAttributeObj->getSource()->getOptionText($product->getData('booking_type'));
                        //    }
                        //    
                        //    if($dropdown_option_label == "Mobile Booking"){
                        //        
                        //        $allow_shipping_steps = false;                                
                        //        
                        //    }elseif($dropdown_option_label == "Service Booking"){
                        //        
                        //        $allow_shipping_steps = false;
                        //    }
                        //    else{
                        //        $allow_shipping_steps = true;
                        //    }
                        //}
                        
                    }
                    
                    
        if (!$this->getQuote()->isVirtual() && $allow_shipping_steps == true) {
            $address = $this->getQuote()->getShippingAddress();
            $addressValidation = $address->validate();
            if ($addressValidation !== true) {
                Mage::throwException(
                    Mage::helper('sales')->__('Please check shipping address information. %s', implode(' ', $addressValidation))
                );
            }
            $method= $address->getShippingMethod();
            $rate  = $address->getShippingRateByCode($method);
            if (!$this->getQuote()->isVirtual() && (!$method || !$rate)) {
                Mage::throwException(Mage::helper('sales')->__('Please specify a shipping method.'));
            }
        }

        $addressValidation = $this->getQuote()->getBillingAddress()->validate();
        if ($addressValidation !== true) {
            Mage::throwException(
                Mage::helper('sales')->__('Please check billing address information. %s', implode(' ', $addressValidation))
            );
        }

        if (!($this->getQuote()->getPayment()->getMethod())) {
            Mage::throwException(Mage::helper('sales')->__('Please select a valid payment method.'));
        }

        return $this;
    }

   
   
}
