<?php

class Megnor_Manufacturer_Block_Adminhtml_Manufacturer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  const XML_ATTRIBUTE_CODE = 'manufacturer/general/attribute_code';  
  	
  public function __construct()
  {
      parent::__construct();
      $this->setId('manufacturerGrid');
      $this->setDefaultSort('manufacturer_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('manufacturer/manufacturer')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('manufacturer_id', array(
          'header'    => Mage::helper('manufacturer')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'manufacturer_id',
      ));
      
     //get Manufecturer's Attribut options 
     $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', Mage::getStoreConfig('manufacturer/general/attribute_code'));
     $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
     $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
     $attributeOptions = $attribute_table->getAllOptions(false);
     foreach($attributeOptions as $key=>$value){
        $manufacturer[$value['value']]=$value['label'];
     }
      //End
      $this->addColumn('manufacturer_name', array(
          'header'    => Mage::helper('manufacturer')->__('Manufacturer Name'),
          'align'     =>'left',
          'index'     =>'manufacturer_name',
          'type'      =>'options',
          'options'   =>$manufacturer,   
      ));
      
      /*$this->addColumn('filename', array(
          'header'    => Mage::helper('manufacturer')->__('Thumbnail'),
          'renderer'  => 'manufacturer/adminhtml_grid_renderer1_image',
          'filter'=>false,
          'align'     =>'left',
          'type'  => 'image',
          'width'     => '100px',
          'index'     => 'filename',
      ));*/

		$this->addColumn('filename', array(
                'header'    => Mage::helper('manufacturer')->__('Image'),
				//'renderer'  => 'manufacturer/adminhtml_grid_renderer_image',  // Enable this option if you want to display Imagtes on Grid
				'filter'    => false,
                'align'     => 'left',
                'index'     => 'filename',
 	            'type' 		=> 'image',
          		'width'     => '100px',
        ));	

		$this->addColumn('title', array(
                'header'    => Mage::helper('manufacturer')->__('Title'),
                'align'     =>'left',
                'index'     => 'title',
        ));
		$this->addColumn('urlkey', array(
                'header'    => Mage::helper('manufacturer')->__('URL Key'),
                'align'     =>'left',
                'index'     => 'urlkey',
        ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('manufacturer')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('manufacturer')->__('Status'),
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
                'header'    =>  Mage::helper('manufacturer')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('manufacturer')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('manufacturer')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('manufacturer')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('manufacturer_id');
        $this->getMassactionBlock()->setFormFieldName('manufacturer');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('manufacturer')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('manufacturer')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('manufacturer/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('manufacturer')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('manufacturer')->__('Status'),
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