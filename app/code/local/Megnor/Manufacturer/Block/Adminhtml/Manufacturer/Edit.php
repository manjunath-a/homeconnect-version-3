<?php

class Megnor_Manufacturer_Block_Adminhtml_Manufacturer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'manufacturer';
        $this->_controller = 'adminhtml_manufacturer';
        
        $this->_updateButton('save', 'label', Mage::helper('manufacturer')->__('Save Manufacturer'));
        $this->_updateButton('delete', 'label', Mage::helper('manufacturer')->__('Delete Manufacturer'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('manufacturer_description') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'manufacturer_description');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'manufacturer_description');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('manufacturer_data') && Mage::registry('manufacturer_data')->getId() ) {
            return Mage::helper('manufacturer')->__("Edit Manufacturer"); //'%s'", $this->htmlEscape(Mage::registry('manufacturer_data')->getManufacturerName()));
        } else {
            return Mage::helper('manufacturer')->__('Add Manufacturer');
        }
    }
}