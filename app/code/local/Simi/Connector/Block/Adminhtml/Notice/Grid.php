<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Madapter Grid Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Simi_Connector_Block_Adminhtml_Notice_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('noticeGrid');
        $this->setDefaultSort('notice_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Simi_Connector_Block_Adminhtml_Banner_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('connector/notice')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Simi_Connector_Block_Adminhtml_Banner_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('notice_id', array(
            'header' => Mage::helper('connector')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'notice_id',
        ));

        $this->addColumn('notice_title', array(
            'header' => Mage::helper('connector')->__('Title'),
            'align' => 'left',
            'index' => 'notice_title',
        ));

        $this->addColumn('website_id', array(
            'header' => Mage::helper('connector')->__('Website'),
            'width' => '750px',
            'index' => 'website_id',
            'renderer' => 'connector/adminhtml_grid_renderer_website',
        ));

        $this->addColumn('device_id', array(
            'header' => Mage::helper('connector')->__('OS'),
            'width' => '750px',
            'index' => 'device_id',
            'renderer' => 'connector/adminhtml_grid_renderer_osystem',
        ));

        // $this->addColumn('notice_sanbox', array(
            // 'header' => Mage::helper('connector')->__('Sandbox'),
            // 'align' => 'left',
            // 'width' => '80px',
            // 'index' => 'notice_sanbox',
            // 'type' => 'options',
            // 'options' => array(
                // 1 => 'Yes',
                // 0 => 'No',
            // ),
        // ));

        $this->addColumn('action', array(
            'header' => Mage::helper('connector')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('connector')->__('Resend'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
            )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

//        $this->addExportType('*/*/exportCsv', Mage::helper('connector')->__('CSV'));
//        $this->addExportType('*/*/exportXml', Mage::helper('connector')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * prepare mass action for this grid
     *
     * @return Magestore_Madapter_Block_Adminhtml_Madapter_Grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('notice_id');
        $this->getMassactionBlock()->setFormFieldName('connector');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('connector')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('connector')->__('Are you sure?')
        ));

        return $this;
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}