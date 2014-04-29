<?php
require_once("Mage/Checkout/controllers/OnepageController.php");


class FME_Bookingreservation_OnepageController extends Mage_Checkout_OnepageController
{
    
    /**
     * save checkout billing address
     */
    public function saveBillingAction()
    {   
                    
                    
        
        
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
//            $postData = $this->getRequest()->getPost('billing', array());
//            $data = $this->_filterPostData($postData);
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
            
            
            
            //////////////////////////////////////
            
            
            
                    $cusAddresId = $customerAddressId;
                    $billing_new_address = $data;
                    
                    $isHomeServiceOrder = false;
                    
                    $allow_shipping_steps = false; // if atleast one non service(physical) product is added, then add shipping steps
                    $dropdown_option_label = '';
                    
                    $a_quote    = Mage::getSingleton('checkout/session')->getQuote();
                    
                    $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();        
                    foreach($items as $iteminfo){         
                        
                        $product = Mage::getModel('catalog/product')->load($iteminfo->getProduct()->getId());                        
                        
                        if($iteminfo->getParentItemId() == '' || $iteminfo->getParentItemId() == null){ //if this is a parent product(for configurable)
                                
                                
                                if(!$product->getIsBookingProduct()){
                            
                                    $allow_shipping_steps = true;
                                    
                                }
                                
                                $dropdownAttributeObj = $product->getResource()->getAttribute("booking_type");
                                
                                if ($dropdownAttributeObj->usesSource()) {
                                      
                                      $dropdown_option_label = $dropdownAttributeObj->getSource()->getOptionText($product->getData('booking_type'));
                                }
                                
                                if($dropdown_option_label == "Mobile Booking"){
                                    
                                    //$allow_shipping_steps = false;                                    
                                    
                                    //remove from qoute session
                                    //$address     = $a_quote->getShippingAddress();
                                    //$address->setCollectShippingRates(false);
                                    //$address->setShippingMethod(null);
                                    //$a_quote->collectTotals()->save();
                                    
                                    
                                    $opts_info = $iteminfo->getOptionByCode('info_buyRequest');
                                    $opts_info = unserialize($opts_info->getValue());
                                    
                                    if($opts_info['fme-use-home-service'] == 'yes'){
                                        $isHomeServiceOrder = true;                               
                                    }
                                    
                                }                        
                                else
                                if($dropdown_option_label == "Service Booking"){
                                    //$allow_shipping_steps = false;
                                    
                                    //remove from qoute session                                    
                                    //$address     = $a_quote->getShippingAddress();
                                    //$address->setCollectShippingRates(false);
                                    //$address->setShippingMethod(null);
                                    //$a_quote->collectTotals()->save();                                    
                                   
                                    
                                }
                                else
                                if($dropdown_option_label != "Service Booking" && $dropdown_option_label != "Mobile Booking"){
                                    
                                    //$allow_shipping_steps = true;
                                }
                                
                                
                                
                        }
                    }
                                   
                    
                    
                    
                    if($isHomeServiceOrder == true){
                        
                            $c_address_value = '';
                            if($cusAddresId != ''){ //in case customer select an address
                                
                                $customer_data = Mage::getModel('customer/address')->load($cusAddresId);        
                                $customer_country = Mage::getModel('directory/country')->loadByCode($customer_data->getCountry());            
                                
                                    if($customer_data['street']){
                                        $c_address_value = $customer_data['street'];
                                    }
                                    if($customer_data->getRegion()){                
                                        $c_address_value = $c_address_value.', '.$customer_data->getRegion();
                                    }
                                    if($customer_data->getCity()){                
                                        $c_address_value = $c_address_value.', '.$customer_data->getCity();
                                    }
                                    if($customer_data->getPostcode()){                
                                        $c_address_value = $c_address_value.' '.$customer_data->getPostcode();
                                    }
                                    if($customer_country->getName()){                
                                        $c_address_value = $c_address_value.', '.$customer_country->getName();
                                    }                
                                    
                            }
                            else
                            if(!empty($billing_new_address)){ // incase customer add new address
                                
                                $customer_country = Mage::getModel('directory/country')->loadByCode($billing_new_address['country_id']);
                                $regionModel = Mage::getModel('directory/region')->load($billing_new_address['region_id']);
                                
                                $c_address_value = $billing_new_address['street'][0].' '.$billing_new_address['street'][1].', '.$regionModel->getName().', '.$billing_new_address['city'].' '.$billing_new_address['postcode'].', '.$customer_country->getName();
                                
                                
                            }
                            
                            
                            $address_distance = Mage::helper('bookingreservation')->getCustomerBookingAddressDistance($c_address_value);        
                            $distance_price = Mage::helper('bookingreservation')->calculateDistancePrice($address_distance);
                                
                                
                            //now add this price to total as Home Service Charges
                            Mage::getSingleton('core/session')->setData('home_service_amount', $distance_price);
                            
                    }
            
            
            
            
            ///////////////////////////////////////
            
            //Next it goes to Payment method, rather then Shipping (for home service, and service products)
            
            
            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);
            
            if (!isset($result['error'])) {
                
                /* check quote for virtual OR HOME SERVICE products added*/
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                    
                } // if there are all service/home service products in qoute
                elseif ($allow_shipping_steps == false){
                    
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                    
                }                
                elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    $result['goto_section'] = 'shipping_method';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );
                
                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
    

    
    
    
    
    
}
