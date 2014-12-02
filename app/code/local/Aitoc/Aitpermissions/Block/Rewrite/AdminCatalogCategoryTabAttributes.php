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
/**
* @copyright  Copyright (c) 2012 AITOC, Inc.
*/

class Aitoc_Aitpermissions_Block_Rewrite_AdminCatalogCategoryTabAttributes 
    extends Mage_Adminhtml_Block_Catalog_Category_Tab_Attributes
{
    /**
     * Set Fieldset to Form
     *
     * @param array $attributes attributes that are to be added
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param array $exclude attributes that should be skipped
     */
    protected function _setFieldset($attributes, $fieldset, $exclude=array())
    {
        parent::_setFieldset($attributes, $fieldset, $exclude);

        if(!Mage::getStoreConfig('admin/sucategories/enable'))
            return;

        $elements = $fieldset->getSortedElements();

        $role = Mage::getSingleton('aitpermissions/role');

        $catId = Mage::app()->getRequest()->getParam('id');

        if(isset($catId)){
            if ($role->isPermissionsEnabled()){
                if(Mage::getModel('aitpermissions/approvecategory')->isCategoryApproved($catId)){
                    //do nothing
                }
                else{
                    foreach($elements as $elem){
                        if($elem->getName() === 'is_active'){
                            $newValues = $this->getAitAllOptions();
                            $elem->setValues($newValues);                            
                        }
                    }
                }
            }
            else{
                if(Mage::getModel('aitpermissions/approvecategory')->isCategoryApproved($catId)){
                    //do nothing
                }
                else{
                    foreach($elements as $elem){
                        if($elem->getName() === 'is_active'){
                            $oldValues = $elem->getValues();
                            $newValues = $this->getAitAllOptionsEmpty();
                            $values = array_merge($oldValues, $newValues);
                            $elem->setValues($values);                          
                        }
                    }
                }                
            }
        }
        else{
            if ($role->isPermissionsEnabled()){
                foreach($elements as $elem){
                    if($elem->getName() === 'is_active'){                        
                        $newValues = $this->getAitAllOptions();
                        $elem->setValues($newValues);
                    }
                }
            }
            else{
                //do nothing
            }
        }       
    }

    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAitAllOptions()
    {        
            $this->_options = array(
                array(
                    'label' => Mage::helper('aitpermissions')->__('AWAITING APPROVE'),
                    'value' =>  '00'
                ),                
            );
        
        return $this->_options;
    }

    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAitAllOptionsEmpty()
    {        
            $this->_options = array(
                array(
                    'label' => Mage::helper('catalog')->__('-- Please Select --'),    
                    'value' =>  '00', 
                ),                
            );
        
        return $this->_options;
    }
}