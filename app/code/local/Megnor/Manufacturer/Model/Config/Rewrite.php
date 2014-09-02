<?php

class Megnor_Manufacturer_Model_Config_Rewrite extends Mage_Core_Model_Config_Data
{ 
    protected function _beforeSave(){
        parent::_beforeSave();
        $originalValue = $this -> getValue();
        $newValue = str_replace(str_split('$#?"\'%&@'),'',$originalValue);
        if($newValue!=$originalValue)
            Mage::throwException('Special symbols are not allowed in URL pattern!');
        
        if((substr($newValue,-1,1)=='/')||(substr($newValue,0,1)=='/'))
            Mage::throwException("Symbol ' / ' must be neither first neither last in URL pattern!");
        if(strpos($newValue,'[brand]')===false)
            Mage::throwException("URL pattern must contain [brand] part!");
        return $this;
    }
    protected function _afterSave(){
        parent::_afterSave();
        Mage::getSingleton('adminhtml/session')->setData('manufacturer_update_stores',true);
        return $this;
    }
    protected function _afterDelete(){
        parent::_afterDelete();
        Mage::getSingleton('adminhtml/session')->setData('manufacturer_update_stores',true);
        return $this;
    }
}