<?php

class FME_Bookingreservation_Model_Mysql4_Bookingreservation_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bookingreservation/bookingreservation');
    }
    
    
    //public function addStoreFilter($store)
    //{
    //    if ($store instanceof Mage_Core_Model_Store) {
    //        $store = array($store->getId());
    //    }
    //    
    //    //$this->getSelect()->join(
    //    //    array('store_table' => $this->getTable('bookingreservation')),
    //    //    'main_table.bookingreservation_id = store_table.bookingreservation_id',
    //    //    array()
    //    //)
    //    //->where('store_table.store_id in (?)', array(0, $store));
    //    
    //    $this->getSelect()->from(array('store_table' => $this->getTable('bookingreservation')), 'store_table.store_id' )->where('store_table.store_id in (?)', array(0, $store));
    //    
    //    return $this;
    //}
    
}