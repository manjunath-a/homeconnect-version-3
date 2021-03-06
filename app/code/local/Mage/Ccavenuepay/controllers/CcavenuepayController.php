<?php
/**
*************************************************************************************
 Please Do not edit or add any code in this file without permission of bluezeal.in.
@Developed by bluezeal.in

Magento version 1.7.0.2                 CCAvenue Version 1.31
                              
Module Version. bz-1.0                 Module release: September 2012
**************************************************************************************
*/
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Ccavenuepay
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */



      
 
class Mage_Ccavenuepay_CcavenuepayController extends Mage_Core_Controller_Front_Action
{
    
    
    protected $_order;

    
    public function getOrder()
    {
        if ($this->_order == null) {
        }
        return $this->_order;
    }

    protected function _expireAjax()
    {
        if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
            exit;
        }
    }

    
    public function getStandard()
    {
        return Mage::getSingleton('Ccavenuepay/standard');
    }

    
    public function redirectAction()
    {
	
		$session = Mage::getSingleton('checkout/session');
		$session->setCcavenuepayStandardQuoteId($session->getQuoteId());
		$order = Mage::getModel('sales/order');
		$order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
		$order->sendNewOrderEmail();
		$order->save();
		
		$this->getResponse()->setBody($this->getLayout()->createBlock('Ccavenuepay/form_redirect')->toHtml());
		$session->unsQuoteId();

    }

    
    public function cancelAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getCcavenuepayStandardQuoteId(true));

        
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
        }

        
		 Mage::getSingleton('checkout/session')->addError("CcavenuePay Payment has been cancelled and the transaction has been declined.");
		$this->_redirect('checkout/cart');
    }

    
    public function  successAction()
    {
        if (!$this->getRequest()->isPost()) {
        $this->cancelAction();
			return false;
        }

        $status = true;

		$response = $this->getRequest()->getPost();		 		
		if (empty($response))  {
            $status = false;
        }
		$AuthDesc="N";

		$WorkingKey = Mage::getStoreConfig('payment/ccavenuepay/workingkey');
		
		if(isset($response["Merchant_Id"])) $Merchant_Id=$response["Merchant_Id"];
		if(isset($response["Amount"])) $Amount= $response["Amount"];
		if(isset($response["Order_Id"])) $Order_Id=$response["Order_Id"];
		if(isset($response["Merchant_Param"])) $Merchant_Param= $response["Merchant_Param"];
		if(isset($response["Checksum"])) $Checksum= $response["Checksum"];
		if(isset($response["AuthDesc"])) $AuthDesc=$response["AuthDesc"];
		
		$ccavenuepay = Mage::getModel('ccavenuepay/method_ccavenuepay');

		$Checksum = $ccavenuepay->verifyChecksum($Merchant_Id, $Order_Id , $Amount,$AuthDesc,$Checksum,$WorkingKey);
		
		if($Checksum=="true" && $AuthDesc=="N")
		{
			$this->getCheckout()->setCcavenuepayErrorMessage('CCAVENUE UNSUCCESS' );   
			$this->cancelAction();
			return false;
		}
		else if($Checksum=="true" && $AuthDesc=="B")
		{
			$this->getCheckout()->setCcavenuepayErrorMessage('CCAVENUE UNSUCCESS' );   
			$this->cancelAction();
			return false;
		}
		else if($Checksum=="false")
		{
			$this->cancelAction();
			return false;
		}
		$order = Mage::getModel('sales/order');
        $order->loadByIncrementId($Order_Id); 
		
		$f_passed_status=Mage::getStoreConfig('payment/ccavenuepay/payment_success_status');
		$message=Mage::helper('Ccavenuepay')->__('Your payment is authorized.');
		$order->setState($f_passed_status, true, $message);
		///////////////////////////////////
		$payment_confirmation_mail = Mage::getStoreConfig('payment/ccavenuepay/payment_confirmation_mail');
		if($payment_confirmation_mail=="1")
		{	
			$order->sendOrderUpdateEmail(true,'Your payment is authorized.');
		}
		////////////////////////////
		$order->save();
		
		$session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getCcavenuepayStandardQuoteId(true));
        
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();

        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
    }
	public function errorAction()
    {
        $this->_redirect('checkout/onepage/');
    }
	public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
}
