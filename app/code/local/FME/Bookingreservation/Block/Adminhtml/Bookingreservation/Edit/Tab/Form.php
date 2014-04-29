<?php

class FME_Bookingreservation_Block_Adminhtml_Bookingreservation_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('bookingreservation_form', array('legend'=>Mage::helper('bookingreservation')->__('Booking information')));
     
      $bookingres_data = Mage::registry('bookingreservation_data')->getData();
      
      //echo "<pre>"; print_r(unserialize(Mage::getModel('bookingreservation/bookingreservation')->load(43)->getReserveOptions()));
      //exit;
      
      
      $all_products = Mage::getModel('bookingreservation/bookingreservation')->loadAllProductsForSelect();      
      $fieldset->addField('product_id', 'select', array(
          'label'     => Mage::helper('bookingreservation')->__('Product Name'),
          'class'     => '',	  
          'required'  => false,
          'name'      => 'product_id',
	  'values'	=> $all_products
      ));
      
      
      
      
      $all_customers = Mage::getModel('bookingreservation/bookingreservation')->loadAllCustomersForSelect();     
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('bookingreservation')->__('Select Customer'),
          'class'     => '',	  
          'required'  => false,
          'name'      => 'customer_id',
	  'values'	=> $all_customers
	  //'after_element_html' => '<small>Email of customer</small>',
      ));
      
      
      
      
      
      $resource = Mage::getSingleton('core/resource');
      $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
      
      $staff_select = $connection->select()->from(array('staff_t' => $resource->getTableName('staffmembers')) )->where('staff_t.status = (?)', 1);
      $staff_data = $connection->fetchAll($staff_select);
      //make dropdown for select
      $staff_dropdown = array();
      $staff_dropdown[] = array('value'     => 0,	'label'     => Mage::helper('bookingreservation')->__('Any One'));
			  
      foreach($staff_data as $staff){			    
		    $staff_dropdown[] = array('value'     => $staff['staffmembers_id'],	'label'     => $staff['member_name'] );			    
      }
      
      
      $fieldset->addField('staffmember_id', 'select', array(
          'label'     => Mage::helper('bookingreservation')->__('Staff Member'),
          'class'     => '',
	  'required'  => false,
          'name'      => 'staffmember_id',
	  'values'    => $staff_dropdown
	  //'after_element_html' => '<small>Email of customer</small>',
      ));
      
      
      
      if($bookingres_data['days_booking'] == ''){
      
	      $fieldset->addField('reserve_day', 'text', array(
		  'label'     => Mage::helper('bookingreservation')->__('Booking Day'),
		  'class'     => 'required-entry',	  
		  'required'  => true,
		  'name'      => 'reserve_day',
		  'after_element_html' => '<small> - Date Format must be (yyyy-mm-dd)</small>',
	      ));
	      
	      
	      
	      $fieldset->addField('reserve_from_time', 'text', array(
		  'label'     => Mage::helper('bookingreservation')->__('Booking From'),
		  'class'     => 'required-entry',	  
		  'required'  => true,
		  'name'      => 'reserve_from_time',
		  'after_element_html' => '<small> - Time Format must be (hh:mm daypart)</small>',
	      ));
	      
	      
	      
	      
	      $fieldset->addField('reserve_to_time', 'text', array(
		  'label'     => Mage::helper('bookingreservation')->__('Booking To'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'reserve_to_time',
		  'values'    => '',
		  'after_element_html' => '<small> - Time Format must be (hh:mm daypart)<br> - Buffer time of '.$bookingres_data['buffer_period'].' Minutes included</small>',
	      ));
	
      }else{
	
	      $fieldset->addField('days_booking', 'text', array(
		  'label'     => Mage::helper('bookingreservation')->__('Booking Days'),
		  'class'     => 'required-entry',	  
		  'required'  => true,
		  'name'      => 'days_booking',
		  'after_element_html' => '<small> - Days Format must be (yyyy-mm-dd,yyyy-mm-dd)</small>',
	      ));
	
      }
      
      
      
      
      
      
      
      
       $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('bookingreservation')->__('Booking Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 'canceled',
                  'label'     => Mage::helper('bookingreservation')->__('Canceled'),
              ),
              array(
                  'value'     => 'closed',
                  'label'     => Mage::helper('bookingreservation')->__('Closed'),
              ),
	      array(
                  'value'     => 'complete',
                  'label'     => Mage::helper('bookingreservation')->__('Complete'),
              ),
	      array(
                  'value'     => 'holded',
                  'label'     => Mage::helper('bookingreservation')->__('On Hold'),
              ),
	      array(
                  'value'     => 'payment_review',
                  'label'     => Mage::helper('bookingreservation')->__('Payment Review'),
              ),
	      array(
                  'value'     => 'pending',
                  'label'     => Mage::helper('bookingreservation')->__('Pending'),
              ),
	      array(
                  'value'     => 'pending_payment',
                  'label'     => Mage::helper('bookingreservation')->__('Pending Payment'),
              ),
	      array(
                  'value'     => 'pending_paypal',
                  'label'     => Mage::helper('bookingreservation')->__('Pending PayPal'),
              ),
	      array(
                  'value'     => 'processing',
                  'label'     => Mage::helper('bookingreservation')->__('Processing'),
              ),
          ),
      ));
      
      
      
      if ( Mage::getSingleton('adminhtml/session')->getBookingreservationData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBookingreservationData());
          Mage::getSingleton('adminhtml/session')->setBookingreservationData(null);
      } elseif ( Mage::registry('bookingreservation_data') ) {
          
			$booking_data = Mage::registry('bookingreservation_data')->getData();
	  
			$resource = Mage::getSingleton('core/resource');
			$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
			
			$prod_select = $connection->select()->from(array('prod_t' => $resource->getTableName('catalog_product_flat_1')), 'prod_t.name' )->where('prod_t.entity_id = (?)', $booking_data['product_id']);
			$prod_data = $connection->fetchRow($prod_select);			
			
			
			if($booking_data['customer_id'] != 0 ){
			  $customer_select = $connection->select()->from(array('customer_t' => $resource->getTableName('customer_entity')), 'customer_t.email' )->where('customer_t.entity_id = (?)', $booking_data['customer_id']);
			  $customer_data = $connection->fetchRow($customer_select);
			}else{
			  $customer_data = array('email' => 'Guest');
			}			  
			
			
			$booking_prod_data = array_merge($booking_data,$prod_data,$customer_data);
			
			
			//echo "<pre>"; print_r($booking_prod_data); exit;
			
	  $form->setValues($booking_prod_data); 
	  
      }
      return parent::_prepareForm();
  }
}