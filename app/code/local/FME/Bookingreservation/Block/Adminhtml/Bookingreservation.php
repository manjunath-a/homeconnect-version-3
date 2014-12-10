<?php
class FME_Bookingreservation_Block_Adminhtml_Bookingreservation extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_bookingreservation';
    $this->_blockGroup = 'bookingreservation';
    $this->_headerText = Mage::helper('bookingreservation')->__('Booking & Reservation Manager');
    $this->_addButtonLabel = Mage::helper('bookingreservation')->__('Add Bookings');
      
    parent::__construct();
    
    //$this->removeButton('add');
    
    
  }
}