<?php
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
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class FME_Bookingreservation_Model_Quote_Address_Total_Homeservice extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    
    protected static $UPDATE_PRICE_TOTAL_COUNT = 0;
    protected static $UPDATE_PRICE_ADD_TOTAL_COUNT = 0;
    
    
    public function __construct()
    {
        $this->setCode('homeservice');
    }

    /**
     * Collect totals information about shipping
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_Sales_Model_Quote_Address_Total_Shipping
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        
        
        $this->_setAmount(0);
	$this->_setBaseAmount(0);
        
        $items = $this->_getAddressItems($address);
	if (!count($items)) {
		return $this; //this makes only address type shipping to come through
	}
        
                
        self::$UPDATE_PRICE_ADD_TOTAL_COUNT++;
        if(self::$UPDATE_PRICE_ADD_TOTAL_COUNT > 1){
            return $this;
        }
        
        
        $isHomeServiceOrder = false;
        $dropdown_option_label = '';
        
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();        
        foreach($items as $iteminfo){         
                        
            $product = Mage::getModel('catalog/product')->load($iteminfo->getProduct()->getId());                        
            
            if($iteminfo->getParentItemId() == '' || $iteminfo->getParentItemId() == null){
                
                $dropdownAttributeObj = $product->getResource()->getAttribute("booking_type");                                
                if ($dropdownAttributeObj->usesSource()) {                                      
                    $dropdown_option_label = $dropdownAttributeObj->getSource()->getOptionText($product->getData('booking_type'));
                }
                
                if($dropdown_option_label == "Mobile Booking"){
                                
                    $opts_info = $iteminfo->getOptionByCode('info_buyRequest');
                    $opts_info = unserialize($opts_info->getValue());
                                
                    if($opts_info['fme-use-home-service'] == 'yes'){
                        $isHomeServiceOrder = true;                               
                    }
                                
                }
            }
        }  
        
        
        if($isHomeServiceOrder == false){
            return $this;
        }
        
                
        
        $home_service_amount = Mage::getSingleton('core/session')->getData('home_service_amount');
        //$address->setGrandTotal($address->getGrandTotal() + $home_service_amount);
        //$address->setBaseGrandTotal($address->getBaseGrandTotal() + $home_service_amount);
        
        $quote = $address->getQuote();
        
        
        
            $exist_amount = $quote->getHomeserviceAmount();
            //$fee = FME_Bookingreservation_Model_Quote_Address_Total_Homeservice::getHomeservice();
            $balance = $home_service_amount;
            $address->setHomeserviceAmount($balance);
            $address->setBaseHomeserviceAmount($balance);
                 
            $quote->setHomeserviceAmount($balance);
            
            $address->setGrandTotal($address->getGrandTotal() + $address->getHomeserviceAmount());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseHomeserviceAmount());
            
            
        
        return $this;
    }

    /**
     * Add shipping totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_Sales_Model_Quote_Address_Total_Shipping
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        
        //self::$UPDATE_PRICE_TOTAL_COUNT++;
        //if(self::$UPDATE_PRICE_TOTAL_COUNT > 1){
        //    return $this;
        //}
        
        
        $isHomeServiceOrder = false;
        $dropdown_option_label = '';
        
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();        
        foreach($items as $iteminfo){         
                        
            $product = Mage::getModel('catalog/product')->load($iteminfo->getProduct()->getId());                        
            
            if($iteminfo->getParentItemId() == '' || $iteminfo->getParentItemId() == null){
                
                $dropdownAttributeObj = $product->getResource()->getAttribute("booking_type");                                
                if ($dropdownAttributeObj->usesSource()) {                                      
                    $dropdown_option_label = $dropdownAttributeObj->getSource()->getOptionText($product->getData('booking_type'));
                }
                
                if($dropdown_option_label == "Mobile Booking"){
                                
                    $opts_info = $iteminfo->getOptionByCode('info_buyRequest');
                    $opts_info = unserialize($opts_info->getValue());
                                
                    if($opts_info['fme-use-home-service'] == 'yes'){
                        $isHomeServiceOrder = true;                               
                    }
                                
                }
            }
        }  
        
        
        if($isHomeServiceOrder == false){
            return $this;
        }
        
        
        //$amount = Mage::getSingleton('core/session')->getData('home_service_amount');       
        $amount = $address->getHomeserviceAmount();
        
        if ($amount != 0) {
            
            $title = Mage::helper('bookingreservation')->__('Home Service Bookings');            
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $title,
                'value' => $amount
            ));
        }
        
        return $this;
    }

    /**
     * Get Shipping label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('sales')->__('Home Service Charges');
    }
}
