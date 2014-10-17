<?php

class Simi_Connector_Block_Adminhtml_Grid_Renderer_Device extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {      
       return Mage::helper('connector')->getNameDeviceById($row->getData('device_id'));
    }

}