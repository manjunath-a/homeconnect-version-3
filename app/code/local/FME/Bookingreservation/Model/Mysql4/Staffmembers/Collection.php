<?php

class FME_Bookingreservation_Model_Mysql4_Staffmembers_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bookingreservation/staffmembers');
    }
}