<?php

class FME_Bookingreservation_Renderer_Reserveday extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $res_day =  $row->getData($this->getColumn()->getIndex());
        
        if($row->getDaysBooking() != ''){
            
            $res_day = $row->getDaysBooking();
        }
        
        
        return $res_day;
        
     
    }
     
}