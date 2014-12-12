<?php
/**
 * Product Testimonial Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   FME
 * @package    Product Testimonial
 * @author     Asif Hussain <support@fmeextensions.com>
 * 	       
 * @copyright  Copyright 2012 © www.fmeextensions.com All right reserved
 */
 
class FME_Bookingreservation_Block_Adminhtml_Staffmembers_Edit_Tab_Memberbookings extends Mage_Adminhtml_Block_Widget_Grid
{
 
    /**
     * Set grid params
     *
     */
    public function __construct(){
      
	parent::__construct();
        $this->setId('member_booking_grid');
        $this->setDefaultSort('bookingreservation_id');
        $this->setUseAjax(true);
	$this->setSaveParametersInSession(true);
        
    }
    
    
     protected function _getReply()
    {	
        return Mage::registry('current_member_bookings');
    }
    

    protected function _prepareCollection(){
      
	$collection = Mage::getModel('bookingreservation/bookingreservation')->getCollection()
								->addFieldToFilter('staffmember_id',$this->_getReply());
      
			$resource = Mage::getSingleton('core/resource');			
			$collection->getSelect()
				      ->joinLeft( array('prod_t'=>$resource->getTableName('catalog_product_flat_1')), 'prod_t.entity_id=main_table.product_id', array('prod_t.name'))
				      ->joinLeft( array('customer_t'=>$resource->getTableName('customer_entity')), 'customer_t.entity_id=main_table.customer_id', array('customer_t.email'))
				      ->joinLeft( array('staff_t'=>$resource->getTableName('staffmembers')), 'staff_t.staffmembers_id=main_table.staffmember_id', array('staff_t.member_name'));
	
	$collection->setOrder('main_table.bookingreservation_id', 'DESC');
	
      
	$this->setCollection($collection);	
	return parent::_prepareCollection();
      
    }
    
    protected function _prepareColumns(){
      
      	
	$this->addColumn('bookingreservation_id', array(
          'header'    => Mage::helper('bookingreservation')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'bookingreservation_id',
	));
  
	$this->addColumn('name', array(
	    'header'    => Mage::helper('bookingreservation')->__('Product Name'),
	    'align'     =>'left',
	    'index'     => 'name',
	    //'renderer'  => 'FME_Bookingreservation_Renderer_Productname'
	));
  
	$this->addColumn('email', array(
	    'header'    => Mage::helper('bookingreservation')->__('Customer Email'),
	    'align'     =>'left',
	    'index'     => 'email',	  
	));
	
	$this->addColumn('member_name', array(
	    'header'    => Mage::helper('bookingreservation')->__('Staff Member'),
	    'align'     =>'left',
	    'index'     => 'member_name',	  
	));
	
	
	
	$this->addColumn('reserve_day', array(
	    'header'    => Mage::helper('bookingreservation')->__('Booking Day'),
	    'align'     =>'left',
	    'index'     => 'reserve_day',
	    'width'     => '80px',
	));
	    
	    
	$this->addColumn('reserve_from_time', array(
	    'header'    => Mage::helper('bookingreservation')->__('Booking From'),
	    'align'     =>'left',
	    'index'     => 'reserve_from_time',
	    'width'     => '80px',
	));
	
	
	$this->addColumn('reserve_to_time', array(
	    'header'    => Mage::helper('bookingreservation')->__('Booking To'),
	    'align'     =>'left',
	    'index'     => 'reserve_to_time',
	    'width'     => '80px',
	    'renderer'  => 'FME_Bookingreservation_Renderer_Reservetotime'
	));
	
  	
	$this->addColumn('status', array(
	    'header'    => Mage::helper('bookingreservation')->__('Status'),
	    'align'     => 'left',
	    'width'     => '80px',
	    'index'     => 'status',	 
	));
	
	
	$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('bookingreservation')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
		      
                        'caption'   => Mage::helper('bookingreservation')->__('Edit'),
                        'url'       => array('base'=> '*/adminhtml_bookingreservation/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
	
	$this->addExportType('*/*/exportMemberBookingsCsv', Mage::helper('bookingreservation')->__('CSV'));
	$this->addExportType('*/*/exportMemberBookingsXml', Mage::helper('bookingreservation')->__('XML'));
	  
	
	
	return parent::_prepareColumns();
      
    }
    
        
    public function getRowUrl($row){
	
	return $this->getUrl('*/adminhtml_bookingreservation/edit', array('id' => $row->getId()));
    }
 
 
 
 
 
 
}