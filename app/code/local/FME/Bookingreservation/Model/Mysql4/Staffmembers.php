<?php

class FME_Bookingreservation_Model_Mysql4_Staffmembers extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the bookingreservation_id refers to the key field in your database table.
        $this->_init('bookingreservation/staffmembers', 'staffmembers_id');
    }
    
    
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {        
          
        //This load the selected products
        $pselect = $this->_getReadAdapter()->select()->from($this->getTable('booking_staff_products'))->where('staffmembers_id = (?)', $object->getId());
 
        if($pdata = $this->_getReadAdapter()->fetchAll($pselect)){
            $prodArray = array();            
            foreach($pdata as $prod_info){                
                $prodArray[] = $prod_info['product_id'];
            }            
            $object->setData('linked_products',$prodArray);
            
        }
        
        
    
        return parent::_afterLoad($object);    
    }
    
    
    
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('staffmembers_id = ?', $object->getId());
        
        //This update the product table
        $this->_getWriteAdapter()->delete($this->getTable('booking_staff_products'), $condition);
        foreach((array)$object->getData('linked_products') as $prod){
            $prodArray = array();
            $prodArray['staffmembers_id'] = $object->getId();
            $prodArray['product_id'] = $prod;
            $this->_getWriteAdapter()->insert($this->getTable('booking_staff_products'),$prodArray);
        }    
        
        return parent::_afterSave($object);        
    }
    
    
     public function getStaffMombersOfProduct($prod_id){
        
        
        //This load the selected products
        $pselect = $this->_getReadAdapter()->select()->from(array('bst' => $this->getTable('booking_staff_products')))->where('product_id = (?)', $prod_id)->where('status = (?)', 1)
                            ->join( array('membert'=>$this->getTable('bookingreservation/staffmembers')), 'bst.staffmembers_id = membert.staffmembers_id', array('membert.*'));
                            
        
        $pdata = $this->_getReadAdapter()->fetchAll($pselect);
        
        return $pdata;
        
        
    }
    
    
}