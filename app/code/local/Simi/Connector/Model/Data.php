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
 * Madapter Helper
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Magestore_Madapter_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getSortOption($sort_option) {
        $sort = array();
        switch ($sort_option) {
            case 'SORT_NAME':
                $sort[0] = 'name';
                $sort[1] = 'ASC';
                break;
            case 'SORT_PRICE_LOW':
                $sort[0] = 'price';
                $sort[1] = 'ASC';
                break;
            case 'SORT_PRICE_HIGH':
                $sort[0] = 'price';
                $sort[1] = 'DESC';
                break;
            default :
                $sort = null;
        }
        return $sort;
    }

    public function checkOffLimit($limit, $offset) {
        $arrayCheck = array();
        if (!isset($limit) || $limit <= 0) {
            $arrayCheck['limit'] = 5;
        } else {
            $arrayCheck['limit'] = $limit;
        }
        if (!isset($offset) || $offset < 0) {
            $arrayCheck['offset'] = 0;
        } else {
            $arrayCheck['offset'] = $offset;
        }
        return $arrayCheck;
    }

    public function encodeJson($label, $data) {
        $json = null;
        try {
            if (count($data))
                $json = Mage::helper('core')->jsonEncode(array($label => $data, 'status' => 'SUCCESS'));
            else
                $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        } catch (Exception $e) {
            $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        return $json;
    }

    public function encodeJsonForCoupon($label, $data) {
        $json = null;
        try {
            if (count($data))
                $json = Mage::helper('core')->jsonEncode(array($label => $data, 'status' => 'SUCCESS'));
            else
                $json = Mage::helper('core')->jsonEncode(array($label => null,
                    'grand_total' => Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal(),
                    'status' => 'FAIL'));
        } catch (Exception $e) {
            $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        return $json;
    }

    public function encodeSpotProductJson($label, $data) {
        $json = null;
        try {
            if (count($data))
                $json = Mage::helper('core')->jsonEncode(array($label => $data, 'choice_title' => $this->getTitleSpotProduct(), 'status' => 'SUCCESS'));
            else
                $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        } catch (Exception $e) {
            $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        return $json;
    }

    public function endcodeReivewJson($label, $data1, $data2) {
        $json = null;
        try {
            if (count($data1))
                $json = Mage::helper('core')->jsonEncode(array($label => $data1, 'count' => $data2, 'status' => 'SUCCESS'));
            else
                $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        } catch (Exception $e) {
            $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        return $json;
    }

    public function encodeOrderJson($label_1,$label_2,$label_3, $data_1, $data_2, $data_3,  $tax, $subTotal,$grandTotal,$discount, $coupon, $message) {
        $json = null;
        try {
            if (count($data_1))
                $json = Mage::helper('core')->jsonEncode(array($label_1 => $data_1,$label_2 => $data_2 ,$label_3=> $data_3, 'tax' => $tax,'sub_total'=> $subTotal,'grand_total' => $grandTotal,'discount'=>$discount,'coupon_code' => $coupon, 'status' => 'SUCCESS'));
            else
                $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL', 'message' => $message));
        } catch (Exception $e) {
            $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        return $json;
    }

    public function encodeJsonSearch($label, $data, $count = 0) {
        $json = null;
        array_shift($data);
        if (!count($data)) {
            return Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        try {
            if ($count)
                $json = Mage::helper('core')->jsonEncode(array($label => $data, 'total' => $count, 'status' => 'SUCCESS'));
            else
                $json = Mage::helper('core')->jsonEncode(array($label => $data, 'status' => 'SUCCESS'));
        } catch (Exception $e) {
            $json = Mage::helper('core')->jsonEncode(array($label => null, 'status' => 'FAIL'));
        }
        return $json;
    }

    public function decodeJson($json) {
        $data = Mage::helper('core')->jsonDecode($json);
        return $data;
    }

    public function getParams($infoProduct, $type) {
        $params = array();
        //$params['uenc'] = Mage::getBlockSingleton('catalog/product_view')->getAddToCartUrl($product);
        //Zend_debug::dump($params['uenc']);
        $params['product'] = $infoProduct->product_id;
        $params['related_product'] = '';
        $params['qty'] = $infoProduct->product_qty;

        $this->setTypeParams($infoProduct, $params, $type);

        return $params;
    }

    public function setTypeParams($infoProduct, &$params, $type) {
        try {
            if (!isset($type)) {
                return;
            }
            if ($type == 'simple') {
                $options_att = array();
                if (isset($infoProduct->options)) {
                    foreach ($infoProduct->options as $options) {
                        if ($options->option_select_type == 'single')
                            $options_att[$options->option_type_id] = $options->option_id;
                        else
                            $options_att[$options->option_type_id][] = $options->option_id;
                    }
                    $params['options'] = $options_att;
                }
                return;
            } elseif ($type == 'grouped') {
                return;
                $params['super_group'];
            } elseif ($type == 'bundle') {
                $options_att = array();
                foreach ($infoProduct->options as $options) {
                    if ($options->option_select_type == 'single')
                        $options_att[$options->option_type_id] = $options->option_id;
                    else
                        $options_att[$options->option_type_id][] = $options->option_id;
                }
                $params['bundle_option'] = $options_att;
                $params['bundle_option_qty'] = array();
                return;
            } elseif ($type == 'configurable') {
                $options_att = array();
                foreach ($infoProduct->options as $options) {
                    $options_att[$options->option_type_id] = $options->option_id;
                }
                $params['super_attribute'] = $options_att;
                return;
            } else {
                return;
            }
        } catch (Exception $e) {
            Mage::log($e);
        }
    }

    public function convertDataBilling($data) {
        $customerAddressId = false;
        if (isset($data['customer_id']) && $data['customer_id'] != '') {
            $customer = Mage::getModel('customer/customer')->load($data['customer_id']);
            $customerAddressId = $customer->getDefaultBilling();
        }
        $name = $this->soptName($data['b_name']);
        $billing = array();
        $billing['address_id'] = '';
        $billing['firstname'] = $name['first_name'];
        $billing['lastname'] = $name['last_name'];
        $billing['company'] = '';
        $billing['street'] = array($data['b_street'], '');
        $billing['city'] = $data['b_city'];
        $billing['region_id'] = $data['b_state_id'];
        $billing['region'] = $data['b_state_name'];
        $billing['postcode'] = $data['b_zip'];
        $billing['country_id'] = $data['b_country_code'];
        $billing['telephone'] = isset($data['b_phone']) == true ? $data['b_phone'] : '';
        $billing['fax'] = isset($data['fax']) == true ? $data['fax'] : '';
        $billing['use_for_shipping'] = '0';
        $billing['save_in_address_book'] = '1';
        $billing['email'] = $data['b_email'];
        $billing['customer_address_id'] = isset($data['b_address_id']) == true ? $data['b_address_id'] : '';
        $billing['customer_password'] = isset($data['customer_password']) == true ? $data['customer_password'] : '';
        $billing['confirm_password'] = isset($data['confirm_password']) == true ? $data['confirm_password'] : '';
        return $billing;
    }

    public function convertDataShipping($data) {
        $customerAddressId = false;
        if (isset($data['customer_id']) && $data['customer_id'] != '') {
            $customer = Mage::getModel('customer/customer')->load($data['customer_id']);
            $customerAddressId = $customer->getDefaultShipping();
        }
        $name = $this->soptName($data['s_name']);
        $shipping = array();
        $shipping['address_id'] = '';
        $shipping['firstname'] = $name['first_name'];
        $shipping['lastname'] = $name['last_name'];
        $shipping['company'] = '';
        $shipping['street'] = array($data['s_street'], '');
        $shipping['city'] = $data['s_city'];
        $shipping['region_id'] = $data['s_state_id'];
        $shipping['region'] = $data['s_state_name'];
        $shipping['postcode'] = $data['s_zip'];
        $shipping['country_id'] = $data['s_country_code'];
        $shipping['telephone'] = isset($data['s_phone']) == true ? $data['s_phone'] : '';
        $shipping['fax'] = '';
        $shipping['email'] = $data['s_email'];
        $shipping['save_in_address_book'] = '1';
        //$shipping['method'] = $data['shipment_method_id'];
        $shipping['customer_address_id'] = isset($data['s_address_id']) == true ? $data['s_address_id'] : '';
        return $shipping;
    }

    public function convertDataAddress($data) {
        $name = $this->soptName($data['name']);
        $address = array();
        $address['firstname'] = $name['first_name'];
        $address['lastname'] = $name['last_name'];
        $address['company'] = '';
        $address['street'] = array($data['street'], '');
        $address['city'] = $data['city'];
        $address['region_id'] = $data['state_id'];
        $address['region'] = $data['state_name'];
        $address['postcode'] = $data['zip'];
        $address['country_id'] = $data['country_code'];
        $address['telephone'] = $data['phone'];
        $address['fax'] = '';
        $address['email'] = $data['email'];
        return $address;
    }

    public function getAddress($data, $customer) {
        return array(
            'address_fullname' => $customer->getName(),
            'address_street' => $data->getStreet(),
            'address_city' => $data->getCity(),
            'address_state' => $data->getRegionId(),
            'address_zip' => $data->getPostcode(),
            'address_country' => $data->getCountryId(),
            'address_phone' => $data->getTelephone(),
            'adddress_email' => $customer->getEmail(),
        );
    }

    public function getAddressToOrder($data, $customer, $address_billing_id, $address_shipping_id) {
        $street = $data->getStreet();
        //Zend_debug::dump(get_class_methods($data));die();        
        return array(
            'address_id' => $data->getId(),
            'address_billing_id' => $address_billing_id,
            'address_shipping_id' => $address_shipping_id,
            'name' => $data->getName(),
            'street' => $street[0],
            'city' => $data->getCity(),
            'state_name' => $data->getRegion(),
            'state_id' => $data->getRegionId(),
            'state_code' => $data->getRegionCode(),
            'zip' => $data->getPostcode(),
            'country_name' => $data->getCountryModel()->loadByCode($data->getCountry())->getName(),
            'country_code' => $data->getCountry(),
            'phone' => $data->getTelephone(),
            'email' => $customer->getEmail(),
        );
    }

    public function getImageProduct($product, $file = null) {
        if ($file) {
            return Mage::helper('catalog/image')->init($product, 'thumbnail', $file)->__toString();
        }
        return Mage::helper('catalog/image')->init($product, 'small_image')->__toString();
    }

    public function soptName($name) {
        $name = explode(" ", $name, 2);
        // Zend_debug::dump($name);die();
        $change_name = array(
            'first_name' => $name[0],
            'last_name' => count($name) == 1 ? $name[0] : $name[1],
        );
        return $change_name;
    }

    public function getAdressBook($customer) {
        $billing = $customer->getPrimaryBillingAddress();
        $shipping = $customer->getPrimaryShippingAddress();
        if (!$shipping && !$billing) {
            return null;
        }
        $shipping_street = $shipping->getStreetFull();
        $billing_street = $billing->getStreetFull();
        $address = array(
            's_address_id' => $shipping->getId(),
            's_name' => $shipping->getName(),
            's_street' => $shipping_street,
            's_city' => $shipping->getCity(),
            's_state_name' => $shipping->getRegion(),
            's_state_code' => $shipping->getRegionCode(),
            's_state_id' => $shipping->getRegionId(),
            's_zip' => $shipping->getPostcode(),
            's_country_name' => $shipping->getCountryModel()->loadByCode($shipping->getCountry())->getName(),
            's_country_code' => $shipping->getCountry(),
            's_phone' => $shipping->getTelephone(),
            's_email' => $customer->getEmail(),
            'b_address_id' => $billing->getId(),
            'b_name' => $billing->getName(),
            'b_street' => $billing_street,
            'b_city' => $billing->getCity(),
            'b_state_name' => $billing->getRegion(),
            'b_state_code' => $billing->getRegionCode(),
            'b_state_id' => $billing->getRegionId(),
            'b_zip' => $billing->getPostcode(),
            'b_country_name' => $billing->getCountryModel()->loadByCode($billing->getCountry())->getName(),
            'b_country_code' => $billing->getCountry(),
            'b_phone' => $billing->getTelephone(),
            'b_email' => $customer->getEmail(),
        );

        return $address;
    }

    public function checkOptions($products, $optionId, $attributes) {
        foreach ($products as $product) {
            foreach ($attributes as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $code = $productAttribute->getAttributeCode();
                if ($product->getData($code) == $optionId) {
                    return $product;
                }
            }
        }
        return null;
    }

    public function setOptions(&$options, $product, $cacheAttribute) {
        foreach ($cacheAttribute as $code => $value) {
            $options[] = $product->getData($code);
        }
    }

    public function getTitleSpotProduct() {
        return (string) Mage::getStoreConfig('madapter/general/spot_product');
    }

    public function getConfig($value) {
        return (string) Mage::getStoreConfig('madapter/general/' . $value);
    }

    public function getMechantInfo() {
        $list = array(
            'about_us' => Mage::getUrl(null, array('_direct' => $this->getConfig('about_us'))),
            'privacy' => Mage::getUrl(null, array('_direct' => $this->getConfig('privacy'))),
            'contact_us' => Mage::getUrl(null, array('_direct' => $this->getConfig('contact_us'))),
            'forgot_pass' => Mage::getUrl('customer/account/forgotpassword/'),
            'status' => 'SUCCESS'
        );
        return $list;
    }

    public function getConfigNotice($value) {
        return (string) Mage::getStoreConfig('madapter/notice/' . $value);
    }

    public function getBanner() {
        $list = array(
            'url' => $this->getConfig('banner_url'),
            'image_path' => $this->getConfig('banner_image'),
            'status' => 'SUCCESS'
        );
        return $list;
    }

    public function getConfigZooz($value) {
        return (string) Mage::getStoreConfig('payment/zooz/' . $value);
    }

    public function getConfigPayPal($value) {
        return (string) Mage::getStoreConfig('payment/paypal_mobile/' . $value);
    }

    public function getConfigBank($value) {
        return (string) Mage::getStoreConfig('payment/transfer_mobile/' . $value);
    }

    public function getPaymentAway() {
        $data = array();
        
        $data[0]['email'] = $this->getConfigPayPal('business_account');
        $data[0]['client_id'] = $this->getConfigPayPal('client_id');
        $data[0]['is_sandbox'] = $this->getConfigPayPal('is_sandbox');
        $data[0]['is_active'] = $this->getConfigPayPal('active');
        $data[0]['payment_method'] = 'PAYPAL_MOBILE';
        $data[0]['title'] = $this->getConfigPayPal('title');        

        $data[1]['email'] = $this->getConfigZooz('account');
        $data[1]['app_key'] = $this->getConfigZooz('zooz_id');
        $data[1]['is_sandbox'] = $this->getConfigZooz('is_sandbox');
        $data[1]['is_active'] = $this->getConfigZooz('active');
        $data[1]['payment_method'] = 'ZOOZ_MOBILE';
        $data[1]['title'] = $this->getConfigZooz('title');

        $data[2]['content'] = $this->getConfigBank('instructions');
        $data[2]['is_active'] = $this->getConfigBank('active');
        $data[2]['payment_method'] = 'BANK_MOBILE';
        $data[2]['title'] = $this->getConfigBank('title');

        $data[3]['is_active'] = Mage::getStoreConfig('payment/cashondelivery/active');
        $data[3]['payment_method'] = 'COD';
        $data[3]['title'] = Mage::getStoreConfig('payment/cashondelivery/title');      

        if (count($data) == 0) {
            return Mage::helper('core')->jsonEncode(array('status' => 'FAIL'));
        }
        return Mage::helper('core')->jsonEncode(array('status' => 'SUCCESS', 'paymentGatewayList' => $data));
    }

    public function checkApiKey($action) {
        $key = $this->getConfig('api_key');
        if (!$key)
            return FALSE;
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
        $$Authkey = '';
        if (isset($head['Auth-Key']))
            $Authkey = $head['Auth-Key'];
        $method = $_SERVER['REQUEST_METHOD'];
        $host = Mage::app()->getRequest()->getHttpHost();
        $SH = $key . $host . $method . $action;
        $SH = hash('sha256', $SH);
        // Zend_debug::dump($SH);die();
        if ($SH == $Authkey)
            return true;
        return FALSE;
    }

}