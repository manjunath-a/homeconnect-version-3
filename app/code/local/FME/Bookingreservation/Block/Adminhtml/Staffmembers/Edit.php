<?php

class FME_Bookingreservation_Block_Adminhtml_Staffmembers_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'bookingreservation';
        $this->_controller = 'adminhtml_staffmembers';
        
        $this->_updateButton('save', 'label', Mage::helper('bookingreservation')->__('Save Staff Member'));
        $this->_updateButton('delete', 'label', Mage::helper('bookingreservation')->__('Delete Staff Member'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('staffmembers_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'staffmembers_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'staffmembers_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('staffmembers_data') && Mage::registry('staffmembers_data')->getId() ) {
            return Mage::helper('bookingreservation')->__("Edit Staff Member '%s'", $this->htmlEscape(Mage::registry('staffmembers_data')->getMemberName()));
        } else {
            return Mage::helper('bookingreservation')->__('Add Staff Member');
        }
    }
}