<?php

class Simi_Popup_Model_Config {

    public function toOptionArray() {
        return array(
            array('value' => '1', 'label' => Mage::helper('core')->__('Alert')),
            array('value' => '2', 'label' => Mage::helper('core')->__('Static Menu'))
			);            
    }

}