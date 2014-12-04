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
 * Simi Model
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Checkout_Country extends Simi_Connector_Model_Abstract {

    public function getCountries() {
        $list = array();
        $flag = false;
        $countries = Mage::getModel('directory/country')->getCollection();

        foreach ($countries as $country) {
            $flag = true;
            $list[] = array(
                'country_code' => $country->getId(),
                'country_name' => $country->getName(),
            );
        }
        $information = '';
        $information = $this->statusSuccess();
        $information['data'] = $list;
        return $information;
    }

    public function getAllowedCountries() {
        $list = array();        
        $country_default = Mage::getStoreConfig('general/country/default');
        $countries = Mage::getResourceModel('directory/country_collection')->loadByStore();
        $cache = null;
        foreach ($countries as $country) {
            if ($country_default == $country->getId()) {
                $cache = array(
                    'country_code' => $country->getId(),
                    'country_name' => $country->getName(),
                    'states' => $this->getStates($country->getId(), 0),
                );
            }
            else{
                $list[] = array(
                    'country_code' => $country->getId(),
                    'country_name' => $country->getName(),
                    'states' => $this->getStates($country->getId(), 0),
                );   
            }            
        }
        if ($cache){
            array_unshift($list, $cache);
        }        
        $information = $this->statusSuccess();
        $information['data'] = $list;

        return $information;
    }

    public function getDefaultCountry() {
        $list = array();
        $country_code = Mage::getStoreConfig('general/country/default');
        $country = Mage::getModel('directory/country')->loadByCode($country_code);
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $currencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        $currencySymbol = Mage::app()->getLocale()->currency($currencyCode)->getSymbol();
        $list[] = array(
            'country_code' => $country->getId(),
            'country_name' => $country->getName(),
            'locale_identifier' => $locale,
            'currency_symbol' => $currencySymbol,
            'currency_code' => $currencyCode,
        );
        $information = $this->statusSuccess();
        $information['data'] = $list;
        return $information;
    }

    public function getCurrencySymbol() {
        $currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
        $information = $this->statusSuccess();
        $information['data'] = array($currencySymbol);
        return $information;
    }

    public function getStates($data, $key = 1) {
        $code = $data;
        if ($key == 1) {
            $code = $data->country_code;
        }
        $list = array();
        if ($code) {
            $states = Mage::getModel('directory/country')->loadByCode($code)->getRegions();
            foreach ($states as $state) {
                $list[] = array(
                    'state_id' => $state->getRegionId(),
                    'state_name' => $state->getName(),
                    'state_code' => $state->getCode(),
                );
            }
            if ($key == 0)
                return $list;
            $information = $this->statusSuccess();
            $information['data'] = $list;
            return $information;
        } else {
            $information = $this->statusError();
            return $information;
        }
    }

}