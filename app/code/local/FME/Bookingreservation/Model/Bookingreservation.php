<?php

class FME_Bookingreservation_Model_Bookingreservation extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bookingreservation/bookingreservation');
    }
    
    
    public function loadAllProductsForSelect(){
        
        $storeId = Mage::app()->getStore()->getId();
         
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter($storeId)
            ->addAttributeToSelect('*');
        
        $prodct_array = array();
        
        
        foreach ($collection as $product) {
            
            //$dropdownAttributeObj = $product->getResource()->getAttribute("booking_type");
            //$dropdown_option_label = '';
            //if ($dropdownAttributeObj->usesSource()) {                                
            //    $dropdown_option_label = $dropdownAttributeObj->getSource()->getOptionText($product->getData('booking_type'));
            //}
            
            //if($dropdown_option_label == "Mobile Booking" || $dropdown_option_label == "Service Booking"){
            
            if($product->getIsBookingProduct()){
                
                $prodct_array[] = array(
                                        'label' => $product->getName(),
                                        'value' => $product->getId()
                                    
                                    );
            }
            
        }
            
        return $prodct_array;
     
    }
    
    
    
    public function loadAllCustomersForSelect(){
        
        
        $storeId = Mage::app()->getStore()->getId();
         
        $collection = Mage::getModel('customer/customer')
                                        ->getCollection()
                                        ->addAttributeToSelect('*');
                                        
        $customer_array[] = array(
                                        'label' => 'Select',
                                        'value' => 0
                                        
                                 );
        
        foreach ($collection as $customer) {
                  
                $customer_array[] = array(
                                        'label' => $customer->getName(),
                                        'value' => $customer->getId()
                                        
                                    );
            
            
        }
        
        //echo "<pre>"; print_r($customer_array); exit;        
        return $customer_array;
     
    }
    
}