<?php

class FME_Bookingreservation_Renderer_Customername extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $customer_id =  $row->getData($this->getColumn()->getIndex());
        
        if($customer_id!= 0 && $customer_id!= ''){
            
            $customer = Mage::getModel('customer/customer')->load($customer_id);
            
            return $customer->getName().' [ '.$customer->getEmail().' ]';
        
        }else{
            
            return "Guest";
        }
        
        return "Guest";        
     
    }
     
}