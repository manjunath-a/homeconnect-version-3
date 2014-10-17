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
 * @package 	Magestore_Mconnect
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Mconnect Index Controller
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
abstract class Simi_Connector_Controller_Action extends Mage_Core_Controller_Front_Action {

    //check app_key
    protected $_data;

    public function preDispatch() {
        parent::preDispatch();
        $enable = (int) Mage::getStoreConfig("connector/general/enable");
        if (!$enable) {
            echo 'Connect was disable!';
            header("HTTP/1.0 503");
            exit();
        }
        if (!$this->isHeader()) {
            echo 'Connect error!';
            header("HTTP/1.0 401 Unauthorized");
            exit();
        }

        $value = $this->getRequest()->getParam('data');
        $this->praseJsonToData($value);
    }

    public function convertToJson($data) {
        $this->setData($data);
        $this->eventChangeData($this->getEventName('_return'), $data);
        $this->_data = $this->getData();
        return Mage::helper('core')->jsonEncode($this->_data);
    }

    public function eventChangeData($event_name, $data) {
        Mage::dispatchEvent($event_name, array('object' => $this, 'data' => $data));
    }

    public function getEventName($last = '') {
        return $this->getFullActionName() . $last;
    }

    public function praseJsonToData($json) {
        $data = json_decode($json);
        $this->setData($data);
        $this->eventChangeData($this->getEventName(), $data);
        $this->_data = $this->getData();
    }

    public function getData() {
        return $this->_data;
    }

    public function setData($data) {
        $this->_data = $data;
    }

    public function _printDataJson($data) {
        echo $this->convertToJson($data);
        header("Content-Type: application/json");
        exit();
    }

    public function getDeviceId() {
        $user_agent = '';
        if ($_SERVER["HTTP_USER_AGENT"]) {
            $user_agent = $_SERVER["HTTP_USER_AGENT"];
        }
        return Mage::helper('connector')->detectMobile($user_agent);
    }

    public function isHeader() {
        if (!function_exists('getallheaders')) {

            function getallheaders() {
                $head = array();
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                        $head[$name] = $value;
                    } else if ($name == "CONTENT_TYPE") {
                        $head["Content-Type"] = $value;
                    } else if ($name == "CONTENT_LENGTH") {
                        $head["Content-Length"] = $value;
                    }
                }
                return $head;
            }

        }

        $head = getallheaders();

        // token is key
        $websiteId = Mage::app()->getWebsite()->getId();
        $keyModel = Mage::getModel('connector/key')->getKey($websiteId);
        $token;
        foreach ($head as $k => $h) {
            if ($k == "Token" || $k == "TOKEN") {
                $token = $h;
            }
        }
        if ($token == $keyModel->getKeySecret())
            return true;
        else
            return false;
    }

    public function setHeader() {
        
    }

}

?>
