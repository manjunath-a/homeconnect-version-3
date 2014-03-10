<?php

class Megnor_AdvancedMenu_Model_Config_Position
{
    
	public function toOptionArray()
    {
       return array(
            array('value'=>'top', 'label'=>Mage::helper('adminhtml')->__('Top')),
            array('value'=>'bottom', 'label'=>Mage::helper('adminhtml')->__('Bottom')),
			array('value'=>'left', 'label'=>Mage::helper('adminhtml')->__('Left')),
			array('value'=>'right', 'label'=>Mage::helper('adminhtml')->__('Right'))
        );
    }
}
