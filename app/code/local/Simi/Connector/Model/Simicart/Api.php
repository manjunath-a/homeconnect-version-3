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
class Simi_Connector_Model_Simicart_Api extends Mage_Core_Model_Abstract {

    const URL = 'https://www.simicart.com/usermanagement/api/';
    const URLS = 'https://www.simicart.com/usermanagement/api/';
    const FUNC = '';
    const GPARAM_KEY = "/key=";

    public $_linkToDev = 'http://dev.simicommerce.com/simicart/usermanagement';

    public function getResponseBody($url) {
        $contents = "";
        if (ini_get('allow_url_fopen') != 1) {
            @ini_set('allow_url_fopen', '1');
        }

        if (ini_get('allow_url_fopen') != 1) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $contents = curl_exec($ch);
            curl_close($ch);
        } else {
            $contents = file_get_contents($url);
        }
        return $contents;
    }

    public function checkKey($key) {
        $url = self::URL;
        $url .= 'check_key';
        $url .= '/key/' . $key;
        $content = $this->getResponseBody($url);
        $result = json_decode($content);
        if ($result->status == "SUCCESS") {
            return true;
        }
        return false;
    }

    public function getListApp($key) {
        $url = self::URL;
        $urls = self::URLS;

        $url .= 'get_apps';
        $urls .= 'get_apps';

        $url .= '/key/' . $key;
        $urls .= '/key/' . $key;
        $content = $this->getResponseBody($url);

        if ($content == "" || $content == null) {
            $content = $this->getResponseBody($urls);
        }

        $result = json_decode($content);
        return $result;
    }

}