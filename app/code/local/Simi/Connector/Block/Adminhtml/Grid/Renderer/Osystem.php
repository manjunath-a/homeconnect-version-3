<?php

class Simi_Connector_Block_Adminhtml_Grid_Renderer_Osystem extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $os = (int) $row->getDeviceId();
        $name = '';
        if ($os == 0) {
            $name = Mage::helper('connector')->__('ALL');
        } elseif ($os == 1) {
            $name = Mage::helper('connector')->__('IOS');
        } else {
            $name = Mage::helper('connector')->__('Android');
        }
        return $name;
    }

}