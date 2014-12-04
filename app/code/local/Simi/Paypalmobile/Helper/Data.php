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
 * @package 	Magestore_Paypalmobile
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Paypalmobile Helper
 * 
 * @category 	Magestore
 * @package 	Magestore_Paypalmobile
 * @author  	Magestore Developer
 */
class Simi_Paypalmobile_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getResponseBody($data, $check) {
        $payPalUser_Id = $this->getConfigPayPal('api_name');
        $payPalPassword = $this->getConfigPayPal('api_pass');
        $payPalSig = $this->getConfigPayPal('signature');

        if ($check == 1) {
            $app_id = $data->app_id;
            $headerArray = array(
                'X-PAYPAL-SERVICE-VERSION: 1.0.0',
                'X-PAYPAL-SECURITY-USERID:' . $payPalUser_Id,
                'X-PAYPAL-SECURITY-PASSWORD:' . $payPalPassword,
                'X-PAYPAL-SECURITY-SIGNATURE:' . $payPalSig,
                'X-PAYPAL-REQUEST-DATA-FORMAT: NV',
                'X-PAYPAL-RESPONSE-DATA-FORMAT: NV',
                'X-PAYPAL-APPLICATION-ID:' . $app_id
            );
            $url = '';
            if ($this->getConfigPayPal('is_sandbox')) {
                $url = "https://svcs.sandbox.paypal.com/AdaptivePayments/PaymentDetails?payKey=" . $data->pay_key . "&requestEnvelope.errorLanguage=en_US";
            } else {

                $url = "https://svcs.paypal.com/AdaptivePayments/PaymentDetails?payKey=" . $data->pay_key . "&requestEnvelope.errorLanguage=en_US";
            }
            $options = array(
                CURLOPT_RETURNTRANSFER => true, // return web page
                CURLOPT_HEADER => false, // don't return headers
                CURLOPT_FOLLOWLOCATION => true, // follow redirects
                CURLOPT_ENCODING => "", // handle all encodings
                // CURLOPT_USERAGENT      => "spider", // who am i
                CURLOPT_AUTOREFERER => true, // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
                CURLOPT_TIMEOUT => 120, // timeout on response
                CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                CURLOPT_SSL_VERIFYPEER => false, // Disabled SSL Cert checks
                CURLOPT_SSL_VERIFYHOST => true,
                CURLOPT_HTTPHEADER => $headerArray
            );
            $content = '';
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt_array($ch, $options);
                $content = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
                if (is_array($e->getMessage())) {
                    return $e->getMessage();
                } else {
                    array($e->getMessage());
                }
            }

            $data_ret = array();
            $ret = array();
            $params = explode("&", $content);
            foreach ($params as $p) {
                list($k, $v) = explode("=", $p);
                $ret[$k] = urldecode($v);
            }
            $data_ret['payment_status'] = $ret['paymentInfoList.paymentInfo(0).transactionStatus'];
            $data_ret['transaction_id'] = $ret['paymentInfoList.paymentInfo(0).transactionId'];
            $data_ret['currency_code'] = $ret['currencyCode'];
            $data_ret['amount'] = $ret['paymentInfoList.paymentInfo(0).receiver.amount'];
            $data_ret['fund_source_type'] = 'PayPal';
            $data_ret['last_four_digits'] = '';
            $data_ret['transaction_email'] = '';

            return $data_ret;
        } else {
            $payKey = $data->response->id;            
            $data_ret = array();
            if ($this->getConfigPayPal('is_sandbox')) {
                $url = "https://api.sandbox.paypal.com/v1/payments/payment/" . $payKey;
            } else {
                $url = "https://api.paypal.com/v1/payments/payment/" . $payKey;
            }
            $options = array(
                CURLOPT_POST => FALSE,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true, // return web page
                CURLOPT_SSL_VERIFYPEER => false, // Disabled SSL Cert checks
                CURLOPT_SSL_VERIFYHOST => true,
            );
            $content = '';
//            Zend_debug::dump($url);die();
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "Authorization:Bearer " . $this->getAccessToken()));
                curl_setopt_array($ch, $options);
                $content = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
                if (is_array($e->getMessage())) {
                    return $e->getMessage();
                } else {
                    array($e->getMessage());
                }
            }

            $content_json = json_decode($content);
            $transactions = $content_json->transactions;
            $funding_instruments = $content_json->payer->funding_instruments;
            $transaction = $transactions[0];
            $funding_instrument = $funding_instruments[0];
            $data_ret['payment_status'] = $transaction->related_resources[0]->sale->state;
            $data_ret['transaction_id'] = $content_json->id;
            $data_ret['currency_code'] = $transaction->amount->currency;
            $data_ret['amount'] = $transaction->amount->total;
            $payer = $content_json->payer;
            if ($payer->payment_method == "credit_card") {
                $data_ret['fund_source_type'] = $funding_instrument->credit_card->type;
                $data_ret['last_four_digits'] = $funding_instrument->credit_card->number;
            } else {
                $data_ret['fund_source_type'] = "PayPal";
                $data_ret['transaction_email'] = '';
            }
            
            return $data_ret;
        }
    }

    public function getAccessToken() {
        $ch = curl_init();
        $clientId = $this->getConfigPayPal('client_id');
        $secret = $this->getConfigPayPal('secret');
        $url = "https://api.paypal.com/v1/oauth2/token";
        if ($this->getConfigPayPal('is_sandbox')) {
            $url = "https://api.sandbox.paypal.com/v1/oauth2/token";
        }
//        Zend_debug::dump($clientId);
//        Zend_debug::dump($secret);
//        die();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $result = curl_exec($ch);
        $token = '';
        if (empty($result))
            die("Error: No response.");
        else {
            $json = json_decode($result);
            $token = $json->access_token;
        }
        curl_close($ch);
        return $token;
    }

    public function getConfigPayPal($value) {
        return (string) Mage::getStoreConfig('payment/paypal_mobile/' . $value);
    }

}