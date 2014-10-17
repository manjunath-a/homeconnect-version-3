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
class Simi_Connector_Model_Key extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('connector/key');
    }

    public function getKey($webId) {
        $collection = $this->getCollection()->addFieldToFilter('website_id', array('eq' => $webId));
        $app = Mage::getModel('connector/app')->getCollection()->addFieldToFilter('website_id', array('eq' => $webId));
		// Zend_debug::dump($collection->getData());die();
        if (!$app->getFirstItem()->getId()) {
            $data = Mage::helper('connector')->getDataDesgin();
            foreach ($data as $item) {
                $model_d = Mage::getModel('connector/design');
                $model_d->setData($item);
                $model_d->setWebsiteId($webId);
                $model_d->save();
                $model_a = Mage::getModel('connector/app');
                $model_a->setData($item);
                $model_a->setWebsiteId($webId);
                $model_a->save();
            }
        }
        return $collection->getFirstItem();
    }

    public function setKey($key, $webId) {
		// $webId = Mage::getBlockSingleton('connector/adminhtml_web_switcher')->getWebsiteId();
        $cache_key = $this->getKey($webId);
        $this->setData('key_secret', $key);
        $this->setData('website_id', $webId);
        if ($cache_key) {
            $this->setId($cache_key->getId());
        }
		
        $this->save();		
    }

}