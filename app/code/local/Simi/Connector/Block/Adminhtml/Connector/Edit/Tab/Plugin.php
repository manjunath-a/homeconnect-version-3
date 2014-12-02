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
 * @package 	Magestore_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Grid Block
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Plugin extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('pluginGrid');
        $this->setDefaultSort('pluginId');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProgram() && $this->getProgram()->getId()) {
            $this->setDefaultFilter(array('in_plugins' => 1));
        }
    }

//    protected function _addColumnFilterToCollection($column) {
//        if ($column->getId() == 'in_plugins') {
//            $productIds = $this->_getSelectedProducts();
//            if (empty($productIds))
//                $productIds = 0;
//            if ($column->getFilter()->getValue())
//                $this->getCollection()->addFieldToFilter('plugin_id', array('in' => $productIds));
//            elseif ($productIds)
//                $this->getCollection()->addFieldToFilter('plugin_id', array('nin' => $productIds));
//            return $this;
//        }
//        return parent::_addColumnFilterToCollection($column);
//    }

    protected function _prepareCollection() {
        $device_id = $this->getRequest()->getParam('device_id');
        $website_id = $this->getRequest()->getParam('website');
        $collection = Mage::getModel('connector/plugin')->getCollection()
                ->addFieldToFilter('device_id', array('eq' => $device_id))
                ->addFieldToFilter('website_id', array('eq' => $website_id));
        //Zend_debug::dump($collection->getData());die();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('plugin_name', array(
            'header' => Mage::helper('connector')->__('Plugin Name'),
            'align' => 'left',
            'index' => 'plugin_name',
        ));
        $this->addColumn('expired_time', array(
            'header' => Mage::helper('connector')->__('Expiration Date'),
            'align' => 'left',
            'type' => 'datetime',
            'index' => 'expired_time',
            'renderer' => 'connector/adminhtml_grid_renderer_date',
        ));

        $this->addColumn('plugin_status', array(
            'header' => Mage::helper('connector')->__('Status'),
            'width' => '90px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => 'Expired',
                1 => 'Enabled',
                2 => 'Disabled',
                3 => 'Trial'
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('connector')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('connector')->__('View Details'),
                    'url' => Mage::getModel('connector/simicart_api')->_linkToStore,
            )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
    }

    /*   public function getRowUrl($row){
      return $this->getUrl('adminhtml/catalog_product/edit', array(
      'id' 	=> $row->getId(),
      'store'	=>$this->getRequest()->getParam('store')
      ));
      } */

    public function getGridUrl() {
        return $this->getUrl('*/*/pluginGrid', array(
                    '_current' => true,
                    'id' => $this->getRequest()->getParam('id'),
                ));
    }

}