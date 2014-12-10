<?php
class FME_Bookingreservation_Block_Bookingreservation extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getBookingreservation()     
     { 
        if (!$this->hasData('bookingreservation')) {
            $this->setData('bookingreservation', Mage::registry('bookingreservation'));
        }
        return $this->getData('bookingreservation');
        
    }
}