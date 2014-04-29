<?php

class FME_Bookingreservation_Block_Adminhtml_Staffmembers_Edit_Tab_Availabilityform extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('staffmembers_form', array('legend'=>Mage::helper('bookingreservation')->__('Member Availability Information')));
      
      
      $fieldset->addField('all_days_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( All Days )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'all_days_start',	  
      ));      
      $fieldset->addField('all_days_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( All Days )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'all_days_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
	  
      ));
      
      
      
      $fieldset->addField('monday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Monday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'monday_start',	  
      ));      
      $fieldset->addField('monday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Monday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'monday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
	  
      ));
      
      
      
      
      $fieldset->addField('tuesday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Tuesday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'tuesday_start',	  
      ));      
      $fieldset->addField('tuesday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Tuesday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'tuesday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
	  
      ));
      
      
      
      $fieldset->addField('wednesday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Wednesday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'wednesday_start',	  
      ));      
      $fieldset->addField('wednesday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Wednesday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'wednesday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
	  
      ));
      
      
      
      
      
      $fieldset->addField('thursday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Thursday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'thursday_start',	  
      ));      
      $fieldset->addField('thursday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Thursday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'thursday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
      ));
      
      
      
      
       $fieldset->addField('friday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Friday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'friday_start',	  
      ));      
      $fieldset->addField('friday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Friday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'friday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
	  
      ));
      
      
      
      
      $fieldset->addField('saturday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Saturday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'saturday_start',	  
      ));      
      $fieldset->addField('saturday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Saturday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'saturday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
	  
      ));
      
      
      
      
      
      $fieldset->addField('sunday_start', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('Start Time ( Sunday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'sunday_start',	  
      ));      
      $fieldset->addField('sunday_end', 'text', array(
          'label'     => Mage::helper('bookingreservation')->__('End Time ( Sunday )'),
          'class'     => '',
          'required'  => false,
          'name'      => 'sunday_end',
	  'after_element_html' => '<p class="note">Time Format: ( hh:mm daypart )</p><br><br><br>'
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
