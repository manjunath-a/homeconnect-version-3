<?php

class FME_Bookingreservation_Block_Adminhtml_Staffmembers_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('staffmembers_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('bookingreservation')->__('Staff Members Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('bookingreservation')->__('Members Information'),
          'title'     => Mage::helper('bookingreservation')->__('Members Information'),
          'content'   => $this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_edit_tab_form')->toHtml(),
      ));
     
      
      $this->addTab('availability_section',array(
          'label'     => Mage::helper('bookingreservation')->__('Members Availability'),
          'title'     => Mage::helper('bookingreservation')->__('Members Availability'),
          'content'   => $this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_edit_tab_availabilityform')->toHtml(),
      ));
      
     
      $this->addTab('booking_section',array(
          'label'     => Mage::helper('bookingreservation')->__('Members Bookings'),
          'title'     => Mage::helper('bookingreservation')->__('Members Bookings'),
          'url'       => $this->getUrl('*/*/memberbookings', array('_current' => true)),
          'class'     => 'ajax',
      ));
      
      
      return parent::_beforeToHtml();
  }
}