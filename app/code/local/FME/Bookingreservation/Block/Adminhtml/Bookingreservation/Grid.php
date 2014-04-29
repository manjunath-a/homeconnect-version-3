<?php

class FME_Bookingreservation_Block_Adminhtml_Bookingreservation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('bookingreservationGrid');
      $this->setDefaultSort('bookingreservation_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      
      $collection = Mage::getModel('bookingreservation/bookingreservation')->getCollection();
		
		      $resource = Mage::getSingleton('core/resource');			
		      $collection->getSelect()
				    ->joinLeft( array('prod_t'=>$resource->getTableName('catalog_product_flat_1')), 'prod_t.entity_id=main_table.product_id', array('prod_t.name'))
				    ->joinLeft( array('customer_t'=>$resource->getTableName('customer_entity')), 'customer_t.entity_id=main_table.customer_id', array('customer_t.email'))
				    ->joinLeft( array('staff_t'=>$resource->getTableName('staffmembers')), 'staff_t.staffmembers_id=main_table.staffmember_id', array('staff_t.member_name'));
			
			
				    
				    
      $collection->setOrder('main_table.bookingreservation_id', 'DESC');
      $collection->addFilterToMap('status', 'main_table.status'); 
      
      //echo "<pre>"; print_r($collection->getData()); exit;
      
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
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
	  'renderer'  => 'FME_Bookingreservation_Renderer_Reserveday'
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
          'type'      => 'options',
          'options'    => array(
                  'canceled' => Mage::helper('bookingreservation')->__('Canceled'),              
                  'closed'   => Mage::helper('bookingreservation')->__('Closed'),      
                  'complete' => Mage::helper('bookingreservation')->__('Complete'),
                  'holded'  => Mage::helper('bookingreservation')->__('On Hold'),
                  'payment_review'  => Mage::helper('bookingreservation')->__('Payment Review'),
                  'pending' => Mage::helper('bookingreservation')->__('Pending'),
                  'pending_payment' => Mage::helper('bookingreservation')->__('Pending Payment'),
                  'pending_paypal' => Mage::helper('bookingreservation')->__('Pending PayPal'),
                  'processing' => Mage::helper('bookingreservation')->__('Processing'),
          ),
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
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('bookingreservation')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('bookingreservation')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    //protected function _prepareMassaction()
    //{
    //    $this->setMassactionIdField('bookingreservation_id');
    //    $this->getMassactionBlock()->setFormFieldName('bookingreservation');
    //
    //    $this->getMassactionBlock()->addItem('delete', array(
    //         'label'    => Mage::helper('bookingreservation')->__('Delete'),
    //         'url'      => $this->getUrl('*/*/massDelete'),
    //         'confirm'  => Mage::helper('bookingreservation')->__('Are you sure?')
    //    ));
    //
    //    $statuses = Mage::getSingleton('bookingreservation/status')->getOptionArray();
    //
    //    array_unshift($statuses, array('label'=>'', 'value'=>''));
    //    $this->getMassactionBlock()->addItem('status', array(
    //         'label'=> Mage::helper('bookingreservation')->__('Change status'),
    //         'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
    //         'additional' => array(
    //                'visibility' => array(
    //                     'name' => 'status',
    //                     'type' => 'select',
    //                     'class' => 'required-entry',
    //                     'label' => Mage::helper('bookingreservation')->__('Status'),
    //                     'values' => $statuses
    //                 )
    //         )
    //    ));
    //    return $this;
    //}

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}




