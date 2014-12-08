<?php

class FME_Bookingreservation_Block_Adminhtml_Staffmembers_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('staffmembers_form', array('legend'=>Mage::helper('bookingreservation')->__('Member information')));
     
      $fieldset->addField('member_name', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'member_name',
      ));

      $fieldset->addField('member_age', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Age'),
          'class'     => '',
          'required'  => false,
          'name'      => 'member_age',
      ));
      
      $fieldset->addField('profession', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Profession'),
          'class'     => '',
          'required'  => false,
          'name'      => 'profession',
      ));

      $fieldset->addField('member_pic', 'image', array(
          'label'     => Mage::helper('bookingreservation')->__('Avatar'),
          'required'  => false,
          'name'      => 'member_pic',
      ));
       
      $fieldset->addField('staff_email', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Email'),
          'class'     => 'validate-email',
         'required'  => true,
          'name'      => 'staff_email',
      ));
       
       
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('bookingreservation')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('bookingreservation')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('bookingreservation')->__('Disabled'),
              ),
          ),
      ));
     
      
      $all_products = Mage::getModel('bookingreservation/staffmembers')->loadBookingProductsForSelect();
      
      $fieldset->addField('linked_products','multiselect',array(
	    'name'      => 'linked_products[]',
            'label'     => Mage::helper('bookingreservation')->__('Select Services (Products)'),
            'title'     => Mage::helper('bookingreservation')->__('Select Services (Products)'),
            'required'  => false,
	    'values'    => $all_products,
	    'after_element_html'	=> '<p class="note">Member will visible against these products</p>'
      ));
      
      
      if ( Mage::getSingleton('adminhtml/session')->getBookingreservationData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBookingreservationData());
          Mage::getSingleton('adminhtml/session')->setBookingreservationData(null);
      } elseif ( Mage::registry('staffmembers_data') ) {
          $form->setValues(Mage::registry('staffmembers_data')->getData());
      }
      return parent::_prepareForm();
  }
}
