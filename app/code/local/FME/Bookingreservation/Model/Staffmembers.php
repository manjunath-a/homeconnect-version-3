<?php

class FME_Bookingreservation_Model_Staffmembers extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bookingreservation/staffmembers');
        
    }
    
    
    public function loadBookingProductsForSelect(){
        
        $storeId = Mage::app()->getStore()->getId();
         
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter($storeId)
            ->addAttributeToSelect('*')
            ->addFieldToFilter('is_booking_product',array('eq' => 1));
        
        $prodct_array = array();
        
        
        foreach ($collection as $product) {
            
            $prodct_array[] = array(
                                        'label' => $product->getName(),
                                        'value' => $product->getId()
                                    
                                    );
        }
            
        return $prodct_array;
     
    }
    
    
   
}