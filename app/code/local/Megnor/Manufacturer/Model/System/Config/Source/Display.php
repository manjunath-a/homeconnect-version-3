<?php

class Megnor_Manufacturer_Model_System_Config_Source_Display
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'yes', 'label'=>Mage::helper('manufacturer')->__('Yes')),
            array('value'=>'no', 'label'=>Mage::helper('manufacturer')->__('No'))
        );
    }}