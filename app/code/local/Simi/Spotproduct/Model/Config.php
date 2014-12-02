<?php

class Simi_Spotproduct_Model_Config {

    public function toOptionArray() {
        return array(
            array('value' => '1', 'label' => Mage::helper('core')->__('Products Best Seller')),
            array('value' => '2', 'label' => Mage::helper('core')->__('Products Most View')),
            array('value' => '3', 'label' => Mage::helper('core')->__('Products New Update')),
            array('value' => '4', 'label' => Mage::helper('core')->__('Products Recently Added')));
    }

}