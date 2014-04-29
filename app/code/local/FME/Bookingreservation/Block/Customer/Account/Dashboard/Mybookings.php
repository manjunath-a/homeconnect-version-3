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
 * @package     Mage_Customer
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer dashboard addresses section
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class FME_Bookingreservation_Block_Customer_Account_Dashboard_Mybookings extends Mage_Core_Block_Template
{
    
    
    public function customerHasBookings(){
        
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        
        if($customer->getId()){
        
            $booking_model = Mage::getModel('bookingreservation/bookingreservation');            
            $booking_colection = $booking_model->getCollection()
                                                ->addFieldToFilter('customer_id',array('eq'=>$customer->getId()));
        
            
            if($booking_colection->getData()){
                
                return true;
            
            }else{
            
                return false;    
            
            }
            
        }
        
        return false;
        
    }
    
    
    
    
    
    public function getCustomerBookings(){
        
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        
        if($customer->getId()){
        
            $booking_model = Mage::getModel('bookingreservation/bookingreservation');            
            $booking_colection = $booking_model->getCollection()
                                                ->addFieldToFilter('customer_id',array('eq'=>$customer->getId()))                                                
                                                ->setOrder('booking_order_id', 'DESC');
            
                                                  
            return $booking_colection->getData();
        }
        
    }
    
    public function getCustomerDaysBookings(){
        
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        
        if($customer->getId()){
        
            $booking_model = Mage::getModel('bookingreservation/bookingreservation');            
            $booking_colection = $booking_model->getCollection()
                                                ->addFieldToFilter('customer_id',array('eq'=>$customer->getId()))
                                                ->addFieldToFilter('main_table.days_booking',array('neq'=> ''))
                                                ->setOrder('booking_order_id', 'DESC');
            
                                                          
            return $booking_colection->getData();
        }
        
    }
    
    
    
    public function getBookingCancelUrl($booking_order_id, $booking_id, $cancel_type){
        
        
        
        $url =  Mage::getUrl('bookingreservation/index/changeBookingStatus').'order_id'. DS .$booking_order_id. DS .'booking_id'. DS .$booking_id. DS .'booking_cancel'. DS .$cancel_type;
        
        return $url;
        
    }
    
    
    
    
    
    
}
