<?php
/**
 * Advanced Permissions
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitpermissions
 * @version      2.8.1
 * @license:     Kl7jRk0he17edeJ6OS19LXc2T80wKqLuOh4O30S6vG
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class Aitoc_Aitpermissions_Model_Source_Admins extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_data = null;

    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

    public function _getAllOptions()
    {
        if(is_null($this->_data)) {
            $this->_data = array('' => '');
            $collection = Mage::getModel('admin/user')
                ->getCollection();
            foreach($collection as $admin) {
                $this->_data[$admin->getId()] = $admin->getUsername();
            }
        }
        return $this->_data;
    }

    public function toOptionArray()
    {
        $array = array(
       //     array('value' => 0, 'label'=>Mage::helper('aitpermissions')->__('')),
        );
        
        /*UPDATE `alfer_m17`.`catalog_eav_attribute` SET `is_visible` = '1' WHERE `catalog_eav_attribute`.`attribute_id` =962 LIMIT 1 ;
        UPDATE `alfer_m17`.`eav_attribute` SET `frontend_input` = 'select',
`frontend_label` = 'Product owner',
`source_model` = 'aitpermissions/source_admins' WHERE `eav_attribute`.`attribute_id` =962 LIMIT 1 ;*/

        $levels = $this->_getAllOptions();

        foreach($levels as $key=>$value)
        {
            //$array[] = array('value' => $key, 'label'=>Mage::helper('aitpermissions')->__(ucfirst($value)));
            $array[] = array('value' => $key, 'label'=>Mage::helper('aitpermissions')->__($value));
        }

        return $array;
    }
    
    public function getOptionArray()
    {
        /*$array = array(
            0 => Mage::helper('aitpermissions')->__('')
        );*/

        $levels = $this->_getAllOptions();

        foreach($levels as $key=>$value)
        {
            $array[$key] = Mage::helper('aitpermissions')->__(ucfirst($value));
        }

        return $array;
    }
    
}