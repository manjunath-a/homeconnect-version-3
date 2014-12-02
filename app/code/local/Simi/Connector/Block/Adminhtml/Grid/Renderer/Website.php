<?php

class Simi_Connector_Block_Adminhtml_Grid_Renderer_Website extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        return Mage::getModel('core/website')->load($row->getWebsiteId())->getName();
    }

}