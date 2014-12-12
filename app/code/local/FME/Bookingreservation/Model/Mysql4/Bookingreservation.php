<?php

class FME_Bookingreservation_Model_Mysql4_Bookingreservation extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the bookingreservation_id refers to the key field in your database table.
        $this->_init('bookingreservation/bookingreservation', 'bookingreservation_id');
    }
}