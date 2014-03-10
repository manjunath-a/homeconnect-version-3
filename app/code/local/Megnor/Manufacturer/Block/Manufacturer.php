<?php
class Megnor_Manufacturer_Block_Manufacturer extends Mage_Core_Block_Template {
	
    public function getManufacturer()     
    { 
        if (!$this->hasData('manufacturer')) {
            $this->setData('manufacturer', Mage::registry('manufacturer_data'));
        }
        return $this->getData('manufacturer');        
    }
	
	public function _toHtml() {
        if (!(bool) Mage::getStoreConfig('manufacturer/general/enable')) {
            return '';
        }
        return parent::_toHtml();
    }
	
	public function getHeading()
    {
        return Mage::getStoreConfig('manufacturer/cmspage/heading');
    }
	

	public function displayName()
    {
        return Mage::getStoreConfig('manufacturer/cmspage/brands_name');
    }
	
	
	public function getPageHeading() {		
		return  Mage::getStoreConfig('manufacturer/standalone/heading');		
    }
	
}