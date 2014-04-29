<?php

class FME_Bookingreservation_Block_Adminhtml_Bookingreservation_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('bookingreservation_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('bookingreservation')->__('Booking Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('bookingreservation')->__('Booking Information'),
          'title'     => Mage::helper('bookingreservation')->__('Booking Information'),
          'content'   => $this->getLayout()->createBlock('bookingreservation/adminhtml_bookingreservation_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}