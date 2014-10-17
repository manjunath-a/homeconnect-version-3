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
class Simi_Connector_Block_Adminhtml_Connector_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('connectorGrid');
        $this->setDefaultSort('connector_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
    }

    /**
     * prepare collection for block to display
     *
     * @return Simi_Connector_Block_Adminhtml_Connector_Grid
     */
    protected function _prepareCollection() {
        $webId = Mage::getBlockSingleton('connector/adminhtml_web_switcher')->getWebsiteId();
        $collection = Mage::getModel('connector/app')->getCollection();
        if ($collection->getSize()) {
            $collection->addFieldToFilter('website_id', array('eq' => $webId));
            $collection->addFieldToFilter('device_id', array('neq' => 2));
        }
//        Zend_debug::dump($collection->getData());die();
//        $list_device = Mage::helper('connector')->getDevice();
//        $collection = new Varien_Data_Collection();
//        $i = 1;
//        foreach ($list_device as $device) {
//            $_thing = new Varien_Object();
//            $_thing->setId($i);
//            $_thing->setDevice($device);
//            $_thing->setAppName('Default');
//            $_thing->setExpiredDate('2014-1-20');
//            $_thing->setStatus(2);
//            $collection->addItem($_thing);
//            $i++;
//        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Simi_Connector_Block_Adminhtml_Connector_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('device_id', array(
            'header' => Mage::helper('connector')->__('Device'),
            'align' => 'center',
            'width' => '200px',
            'index' => 'device_id',
            'renderer' => 'connector/adminhtml_grid_renderer_device',
        ));

        $this->addColumn('app_name', array(
            'header' => Mage::helper('connector')->__('App name'),
            'index' => 'app_name',
        ));

        // $this->addColumn('expired_date', array(
        // 'header' => Mage::helper('connector')->__('Expiration date'),
        // 'index' => 'expired_date',
        // 'type' => 'datetime',
        // 'width' => '200px',
        // 'renderer' => 'connector/adminhtml_grid_renderer_date',
        // ));
        // $this->addColumn('status', array(
        // 'header' => Mage::helper('connector')->__('Status'),
        // 'align' => 'left',
        // 'width' => '200px',
        // 'index' => 'status',
        // 'type' => 'options',
        // 'options' => array(
        // 3 => 'Expired',
        // 1 => 'Enabled',
        // 0 => 'Disabled',
        // 2 => 'Trial'
        // ),
        // ));
        $webId = Mage::getBlockSingleton('connector/adminhtml_web_switcher')->getWebsiteId();
        $this->addColumn('action', array(
            'header' => Mage::helper('connector')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('connector')->__('Edit'),
                    'url' => array('base' => '*/*/edit/website/' . $webId),
                    'field' => 'id',
                    'field' => 'device_id'
            )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        // $this->addExportType('*/*/exportCsv', Mage::helper('connector')->__('CSV'));
        // $this->addExportType('*/*/exportXml', Mage::helper('connector')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row) {
        $webId = Mage::getBlockSingleton('connector/adminhtml_web_switcher')->getWebsiteId();
        return $this->getUrl('*/*/edit', array('id' => $row->getId(), 'website' => $webId, 'device_id' => $row->getDeviceId()));
    }

}