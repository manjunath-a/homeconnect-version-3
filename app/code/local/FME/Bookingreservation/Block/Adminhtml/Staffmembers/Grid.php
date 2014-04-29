<?php

class FME_Bookingreservation_Block_Adminhtml_Staffmembers_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('staffmembersGrid');
      $this->setDefaultSort('staffmembers_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('bookingreservation/staffmembers')->getCollection();
      
      
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('staffmembers_id', array(
          'header'    => Mage::helper('bookingreservation')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'staffmembers_id',
      ));

      $this->addColumn('member_name', array(
          'header'    => Mage::helper('bookingreservation')->__('Name'),
          'align'     =>'left',
          'index'     => 'member_name',
      ));
      
      $this->addColumn('staff_email', array(
          'header'    => Mage::helper('bookingreservation')->__('Email'),
          'align'     =>'left',
          'index'     => 'staff_email',
      ));
      
      $this->addColumn('member_age', array(
          'header'    => Mage::helper('bookingreservation')->__('Age'),
          'align'     =>'left',
          'index'     => 'member_age',
      ));

      $this->addColumn('profession', array(
          'header'    => Mage::helper('bookingreservation')->__('Profession'),
          'align'     =>'left',
          'index'     => 'profession',
      ));
      
      $this->addColumn('member_pic', array(
          'header'    => Mage::helper('bookingreservation')->__('Avatar'),
          'align'     =>'left',
	  'renderer'  => 'FME_Bookingreservation_Block_Adminhtml_Staffmembers_Renderer_Avatar',
          'index'     => 'member_pic',
      ));
      
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('bookingreservation')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('bookingreservation')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
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

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('staffmembers_id');
        $this->getMassactionBlock()->setFormFieldName('staffmembers');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('bookingreservation')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('bookingreservation')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('bookingreservation/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('bookingreservation')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('bookingreservation')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
