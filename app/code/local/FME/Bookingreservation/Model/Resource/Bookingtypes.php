<?php

class FME_Bookingreservation_Model_Resource_Bookingtypes extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    const REGULAR_BOOKING	= 0;
    const SERVICE_BOOKING	= 1;
    const MOBILE_BOOKING	= 2;
    
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                            'label' => '',
                            'value' =>  ''
                ),
                array(
                            'label' => 'Service Booking',
                            'value' =>  self::SERVICE_BOOKING
                ),
                
                array(
                            'label' => 'Mobile Booking',
                            'value' =>  self::MOBILE_BOOKING
                ),
            );
        }
        
        return $this->_options;
    }
    
    
    public function toOptionArray()
    {      
        return $this->_options;
                
    }
}