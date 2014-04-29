<?php

class FME_Bookingreservation_Block_Adminhtml_Bookingreservation_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'bookingreservation';
        $this->_controller = 'adminhtml_bookingreservation';
        
        $this->_updateButton('save', 'label', Mage::helper('bookingreservation')->__('Save Booking'));
        $this->_updateButton('delete', 'label', Mage::helper('bookingreservation')->__('Delete Booking'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('bookingreservation_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'bookingreservation_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'bookingreservation_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('bookingreservation_data') && Mage::registry('bookingreservation_data')->getId() ) {
            return Mage::helper('bookingreservation')->__("Edit Booking ", $this->htmlEscape(Mage::registry('bookingreservation_data')->get()));
        } else {
            return Mage::helper('bookingreservation')->__('Add Booking');
        }
    }
}