<?php


class FME_Bookingreservation_Block_Onepage extends Mage_Checkout_Block_Onepage
{
    /**
     * Get 'one step checkout' step data
     *
     * @return array
     */
    public function getSteps()
    {
        $steps = array();
        
        
        
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
                        //        
                        //        $allow_shipping_steps = true; 
                        //    }
                        //}
                        
                        
                    }     
        
        if($allow_shipping_steps == false){
            
            $stepCodes = array('login', 'billing', 'payment', 'review');    
            
        }else{
            
            $stepCodes = $this->_getStepCodes();
        }
        
        
        
        if ($this->isCustomerLoggedIn()) {
            $stepCodes = array_diff($stepCodes, array('login'));
        }

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
        
        return $steps;
    }

   
}
