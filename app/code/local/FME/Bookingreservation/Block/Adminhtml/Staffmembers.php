<?php
class FME_Bookingreservation_Block_Adminhtml_Staffmembers extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_staffmembers';
    $this->_blockGroup = 'bookingreservation';
    $this->_headerText = Mage::helper('bookingreservation')->__('Staff Members Manager');
    $this->_addButtonLabel = Mage::helper('bookingreservation')->__('Add Staff Members');
    parent::__construct();
  }
}