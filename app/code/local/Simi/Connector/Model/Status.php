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
 * Connector Status Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @author  	Magestore Developer
 */
class Simi_Connector_Model_Status extends Varien_Object {

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray() {
        return array(
            self::STATUS_ENABLED => Mage::helper('connector')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('connector')->__('Disabled')
        );
    }

    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptionHash() {
        $options = array();
        foreach (self::getOptionArray() as $value => $label)
            $options[] = array(
                'value' => $value,
                'label' => $label
            );
        return $options;
    }

    static public function getWebsite() {
        $options = array();
        $options[] = array(
            'value' => 0,
            'label' => Mage::helper('core')->__('All'),
        );
        $collection = Mage::helper('connector')->getWebsites();
        foreach ($collection as $item) {
            $options[] = array(
                'value' => $item->getId(),
                'label' => $item->getName(),
            );
        }
        return $options;
    }

    public function getWebGird() {
        $collection = Mage::helper('connector')->getWebsites();
        $options = array();
        $options[0] = Mage::helper('core')->__('All');
        foreach ($collection as $item) {
            $options[$item->getId()] = $item->getName();
        }
        return $options;
    }

}