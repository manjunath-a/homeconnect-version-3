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
 * @category 	Simi
 * @package 	Simi_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Model Switch
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Switch extends Simi_Connector_Model_Abstract {

    public function getCurrentWebsiteId() {
        return Mage::app()->getStore()->getWebsiteId();
    }

    public function getCurrentGroupId() {
        return Mage::app()->getStore()->getGroupId();
    }

    public function getCurrentStoreId() {
        return Mage::app()->getStore()->getId();
    }

    public function getRawStores() {
        if (!$this->hasData('raw_stores')) {
            $websiteStores = Mage::app()->getWebsite()->getStores();
            $stores = array();
            foreach ($websiteStores as $store) {
                /* @var $store Mage_Core_Model_Store */
                if (!$store->getIsActive()) {
                    continue;
                }
                $store->setLocaleCode(Mage::getStoreConfig('general/locale/code', $store->getId()));

                $stores[$store->getGroupId()][$store->getId()] = $store;
            }
            $this->setData('raw_stores', $stores);
        }
        return $this->getData('raw_stores');
    }

    public function getStores() {        
        if (!$this->getData('stores')) {
            $data = array();
            $rawStores = $this->getRawStores();

            $groupId = $this->getCurrentGroupId();

            if (!isset($rawStores[$groupId])) {
                $stores = array();
            } else {
                $stores = $rawStores[$groupId];
            }
            $this->setData('stores', $stores);
            foreach ($stores as $store) {
                $data[] = array(
                    'store_id' => $store->getId(),
                    'store_name' => $store->getName(),
					'store_code' => $store->getCode(),
                );
            }
            $information = $this->statusSuccess();            
            $information['data'] = $data;
            return $information;
        }else{
            $information = $this->statusSuccess();            
            $information['data'] = array();
            return $information;
        }        
    }

}
