<?php

class FME_Bookingreservation_Renderer_Reservetotime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $booking_to_time =  $row->getData($this->getColumn()->getIndex());
        
        if($booking_to_time != ''){
        
            $booking_buffer_period = $row->getBufferPeriod();        
            $booking_to_time = Mage::helper('bookingreservation')->conv($booking_to_time);
            $booking_to_time = $booking_to_time-$booking_buffer_period;
            $booking_to_time = Mage::helper('bookingreservation')->toConv($booking_to_time);
        }
        
        return $booking_to_time;
     
    }
     
}