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
class Simi_Connector_Model_App extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('connector/app');
    }

    public function deleteList($website_id) {
        $model = $this;

        $collection = $this->getCollection()
                ->addFieldToFilter('website_id', array('eq' => $website_id));

        foreach ($collection as $item) {
            $model->setAppName("N/A");
            $model->setId($item->getId())->save();
        }
        return;
    }

    public function saveList($data, $website_id) {
        $status = $data->status;
        //Zend_debug::dump($data->data);die();
        if ($status == 'SUCCESS') {
            $list = $data->app_list;
            foreach ($list as $item) {
                $model = $this;
                $collection = $model->getCollection()
                        ->addFieldToFilter('website_id', array('eq' => $website_id))
                        ->addFieldToFilter('device_id', array('eq' => $item->device_id));
                $model->setAppName($item->name);
                $model->setExpiredTime($item->expired_time);
                $model->setStatus($item->status);
                $model->setId($collection->getFirstItem()->getId())->save();
            }
        }
        return;
    }

    // get app with expired_time  <= today, so still active
    public function checkExpriedTime($today) {
        $collection = $this->getCollection()
                ->addFieldToFilter('expired_time', array('to' => $today, 'data' => true))
                ->addFieldToFilter('status', array('nin' => array(0, 2)));
        foreach ($collection as $item) {
            $item->setStatus(0);
        }
        $collection->save();
    }

    public function getDeviceById($id) {
        return $this->load($id)->getDeviceId();
    }

    public function getAppByWebDevice($web_id, $device_id) {
        $collection = $this->getCollection()
                ->addFieldToFilter('website_id', array('eq' => $web_id))
                ->addFieldToFilter('device_id', array('eq' => $device_id));
        return $collection->getFirstItem();
    }

    //save category 21-4-2013
    public function saveCategories($web_id, $categories) {
        $collection = $this->getCollection()->addFieldToFilter('website_id', array('eq' => $web_id));
        // Zend_Debug::dump($collection->getData());die();
        foreach ($collection as $item) {
            $item->setData("categories", $categories);
        }
        $collection->save();
    }

}