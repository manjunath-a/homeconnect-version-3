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
 * Connector Observer Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @author  	Magestore Developer
 */
class Simi_Connector_Model_Observer {
	
    public function paymentMethodIsActive($observer) {
        $result = $observer['result'];
        $method = $observer['method_instance'];
        //$store = $quote ? $quote->getStoreId() : null;            
        if ($method->getCode() == 'transfer_mobile') {
            if (Mage::app()->getRequest()->getControllerModule() != 'Simi_Connector') {
                $result->isAvailable = false;
            }
        }
    }

    public function addCondition($observer) {
        $object = $observer->getObject();
        $data = $object->getCacheData();
        $agreements = Mage::helper('connector/checkout')->getAgreements();

        $condition = array();
        foreach ($agreements as $agreement) {
            if ($agreement->getIsHtml()) {
                $condition[] = array(
                    'id' => $agreement->getId(),
                    'name' => $agreement->getName(),
                    'title' => $agreement->getCheckboxText(),
                    'content' => $agreement->getContent(),
                );
            } else {
                $condition[] = array(
                    'id' => $agreement->getId(),
                    'name' => $agreement->getName(),
                    'title' => $agreement->getCheckboxText(),
                    'content' => nl2br(Mage::helper('connector')->escapeHtml($agreement->getContent())),
                );
            }
        }
        $data['condition'] = $condition;
        $object->setCacheData($data, "simi_connector");
        return;
    }

    public function editProfile($observer) {
        $object = $observer->getObject();
        $data = $object->getCacheData();
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        if (Mage::getStoreConfig('customer/address/dob_show') != "" && $customer->getDob() != NULL) {
            $dob = explode(" ", $customer->getDob());
            $bthday = explode("-", $dob[0]);
            $data['year'] = $bthday[0];
            $data['month'] = $bthday[1];
            $data['day'] = $bthday[2];
        }

        if (Mage::getStoreConfig('customer/address/taxvat_show') != "" && $customer->getTaxvat() != NULL) {
            $data['taxvat'] = $customer->getTaxvat();
        }

        if (Mage::getStoreConfig('customer/address/gender_show') != "" && $customer->getGender() != NULL) {
            $data['gender'] = $customer->getGender();
        }

        if (Mage::getStoreConfig('customer/address/prefix_show') != "" && $customer->getPrefix()) {
            $data['prefix'] = $customer->getPrefix();
        }
        if (Mage::getStoreConfig('customer/address/suffix_show') != "" && $customer->getSuffix()) {
            $data['suffix'] = $customer->getSuffix();
        }
        $data['user_name'] = $customer->getFirstname() . " " . $customer->getLastname();
        $object->setCacheData($data, "simi_connector");
    }
	
	public function addOtherInfo($observer) {
		$object = $observer->getObject();
        $product = $observer->getProduct();
        $data = $object->getCacheData();
		
		$other_info = array();
		if (Mage::helper('core/data')->isModuleEnabled("DerModPro_BasePrice")) {
            $value = Mage::helper("baseprice")->getBasePriceLabel($product, false);
			
            if (isset($value) && $value != null)
                $other_info[] = array("label" => "", "value" => $value);
        }
		
		$shipping_condition = Mage::getStoreConfig("connector/general/cms_shipping_condition");		
		if($shipping_condition != ""){
			$shipping = Mage::getModel('connector/cms')->load($shipping_condition);
			$content = $shipping->getCmsContent();
			$title = $shipping->getCmsTitle();
			$other_info[] = array("label" => "", "value" => $title, "click" => "1" , "content" => $content);										
		}				
        $data["other_info"] = $other_info;		
        $object->setCacheData($data, "simi_connector"); 
		
	}
}
