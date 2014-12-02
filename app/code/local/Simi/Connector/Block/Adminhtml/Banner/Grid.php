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
class Simi_Connector_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('bannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Simi_Connector_Block_Adminhtml_Banner_Grid
     */
    protected function _prepareCollection() {
        $webId = 0;
        $collection = Mage::getModel('connector/banner')->getCollection();
        if ($this->getRequest()->getParam('website')){            
            $webId = $this->getRequest()->getParam('website');
            $collection->addFieldToFilter('website_id', array('eq'=>$webId));
        }                
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Simi_Connector_Block_Adminhtml_Banner_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('banner_id', array(
            'header' => Mage::helper('connector')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'banner_id',
        ));

        $this->addColumn('banner_title', array(
            'header' => Mage::helper('connector')->__('Title'),
            'align' => 'left',
            'index' => 'banner_title',
        ));

        $this->addColumn('banner_url', array(
            'header' => Mage::helper('connector')->__('URL'),
            'width' => '550px',
            'index' => 'banner_url',
        ));
        
        $this->addColumn('website_id', array(
            'header' => Mage::helper('connector')->__('Website'),
            'align' => 'left',
            'width' => '200px',
            'index' => 'website_id',
            'type' => 'options',
            'options' => Mage::getSingleton('connector/status')->getWebGird(),
             'filter' => false
        ));
        
        $this->addColumn('status', array(
            'header' => Mage::helper('connector')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('connector')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('connector')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
            )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('connector')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('connector')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * prepare mass action for this grid
     *
     * @return Magestore_Madapter_Block_Adminhtml_Madapter_Grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('connector');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('connector')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('connector')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('connector/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('connector')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('connector')->__('Status'),
                    'values' => $statuses
            ))
        ));
        return $this;
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row) {
        $webId = Mage::getBlockSingleton('connector/adminhtml_web_switcher')->getWebsiteId();
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}