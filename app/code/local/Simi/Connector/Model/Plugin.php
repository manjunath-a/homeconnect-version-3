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
 * Madapter Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Simi_Connector_Model_Plugin extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('connector/plugin');
    }

    public function deleteList($web_id) {
        $collection = $this->getCollection()
                ->addFieldToFilter('website_id', array('eq' => $web_id));
        foreach ($collection as $item) {
            $item->delete();
        }
        $collection->save();
    }

    public function saveList($data, $web_id) {
        $status = $data->status;
        if ($status == 'SUCCESS') {
            $list = $data->plugin_list;
            /**
             *  list[
             *      {
             *         version : 12,
             *         name : test,
             *         expired_time : 2014-1-2,
             *         plaform : 2, 
             *         status : 1,
             *         plugin_code: 1  
             *      },
             *       {
             *      },
             *  ]
             */
            foreach ($list as $item) {
                $it = $this->getItem($item->plugin_code);
                $model = $this;
                $model->setPluginName($item->name);
                $model->setPluginVersion($item->version);
                $model->setExpiredTime($item->expired_time);
                $model->setPluginCode($item->plugin_code);
                $model->setStatus($item->status);
                $model->setDeviceId($item->plaform);
                $model->setWebsiteId($web_id);
                $model->setPluginSku($item->plugin_sku);
                $model->setId($it)->save();
            }
        }

        return;
    }

    public function getItem($code) {
        $collection = $this->getCollection()
                ->addFieldToFilter('plugin_code', array('eq' => $code));
        if ($collection->getSize()) {
            return $collection->getFirstItem()->getId();
        }
        return null;
    }

    // get plugin with expired_time  <= today, so still active
    public function checkExpriedTime($today) {
        $collection = $this->getCollection()
                ->addFieldToFilter('expired_time', array('to' => $today, 'data' => true))
                ->addFieldToFilter('status', array('nin' => array(0, 1)));
        foreach ($collection as $item) {
            $item->setStatus(1);
        }
        $collection->save();
    }

    public function getListPlugin($device_id) {
        if ($device_id == 2) {
            $device_id = 1;
        }
		
        $website = Mage::app()->getStore()->getWebsiteId();
        $collection = $this->getCollection()
                ->addFieldToFilter('website_id', array('eq' => $website))
                ->addFieldToFilter('device_id', array('eq' => $device_id))
                ->addFieldToFilter('status', array('nin' => array(0, 2)));
        return $collection;
    }

    public function checkPlugin($sku) {
        $website = Mage::app()->getStore()->getWebsiteId();
        $collection = $this->getCollection()
                ->addFieldToFilter('website_id', array('eq' => $website))
                ->addFieldToFilter('plugin_sku', array('eq' => $sku))
                ->addFieldToFilter('status', array('nin' => array(0, 2)));
        return $collection->getFirstItem()->getId();
    }

}