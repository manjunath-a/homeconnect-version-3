<?php

class FME_Bookingreservation_Model_Observer
{
    
    private static $_counterIncrement = 1;
    
        const XML_PATH_EMAIL_RECIPIENT  = 'bookingreservation/reply/recipient_email';
	const XML_PATH_EMAIL_SENDER     = 'bookingreservation/reply/sender_email_identity';
	const XML_PATH_EMAIL_TEMPLATE   = 'bookingreservation/reply/email_template';

    
    public function checkOptionsSelectedBefore($info){
        
        $product = $info->getEvent()->getProduct();
           
	   
        if($product->getIsBookingProduct()){                
        
	
		if($product->getIsFullDayBooking()){
		    
		    $this->checkOptionsForDayBookings($info);		    
		    
		}else{
		    
		    $this->checkOptionsForBookings($info);
		    
		}      
	    
        
        }
        
        
        
    }
    
    
    
    public function checkOptionsForDayBookings($info){
	
	$product = $info->getEvent()->getProduct();
	
	    $booking_days = $_POST['booking-selected-days'];
            
	    //check these selected days, whether they are reserve for this product
	    
	    if($booking_days == ''){
                
                Mage::app()->getResponse()->setRedirect($product->getProductUrl());
                Mage::app()->getResponse()->sendResponse();
                
                Mage::getSingleton('core/session')->addNotice(Mage::helper('bookingreservation')->__('Please specify the Booking & Reservation option(s)'));               
                exit;
		
            }else{
		
		
		if($duplicate_days = Mage::helper('bookingreservation')->isCurrentSelectedDaysAreFree($booking_days, $product)){ //returns array of duplicate days otherwise false
                    
		    	$duplicate_days = implode(',',$duplicate_days);
			
                        Mage::app()->getResponse()->setRedirect($product->getProductUrl());   
                        Mage::app()->getResponse()->sendResponse();
                        
                        Mage::getSingleton('core/session')->addNotice(Mage::helper('bookingreservation')->__('Sorry, following days ( '.$duplicate_days.' ) are booked already'));
                        exit;
                }
		
		
	    }
	    
	    
	    
    }
    
   
    
    
    public function checkOptionsForBookings($info){
	
	$product = $info->getEvent()->getProduct();
	
	    $booking_date = '';
            $from_time = '';
            $to_time = '';
            $staffmember_id = 0;
            
            if($_POST){
               
               $booking_date = $_POST['fme_calendar_date'];
               
               $from_time = $_POST['fme_booking_time_from']['hours'].':'.$_POST['fme_booking_time_from']['minutes'].' '.$_POST['fme_booking_time_from']['daypart']; 
                
               $to_time = $_POST['fme_booking_time_to']['hours'].':'.$_POST['fme_booking_time_to']['minutes'].' '.$_POST['fme_booking_time_to']['daypart']; 
                
               $staffmember_id = $_POST['booking_staff_members'];               
            }            
            
	    
	    if($from_time == $to_time){
		
		Mage::app()->getResponse()->setRedirect($product->getProductUrl());   
                Mage::app()->getResponse()->sendResponse();
                
                Mage::getSingleton('core/session')->addNotice(Mage::helper('bookingreservation')->__('Please select time schedule'));               
                exit;
	    }
	    
	    
	    
            if($booking_date=='' || $from_time=='' || $to_time==''){
                
                Mage::app()->getResponse()->setRedirect($product->getProductUrl());   
                Mage::app()->getResponse()->sendResponse();
                
                Mage::getSingleton('core/session')->addNotice(Mage::helper('bookingreservation')->__('Please specify the Booking & Reservation option(s)'));               
                exit;
		
            }else{
            
                // check whether the selected time is reserve against that product, and that day
               
                    $store_id = Mage::app()->getStore()->getStoreId();
                    $product_id = $product->getId();
                    
                    $model = Mage::getModel('bookingreservation/bookingreservation');
                    
                    $booking_collection = $model->getCollection()                                                
                                                ->addFieldToFilter( 'reserve_day', array('eq'=> $booking_date))
                                                ->addFieldToFilter( 'store_id', array('eq'=> $store_id))
						->addFieldToFilter( 'status', array('in'=> array('processing','complete')));
		    
		    
                    // check over all reserve time of this member
                    if($staffmember_id > 0 && $staffmember_id != ''){
                        $booking_collection->addFieldToFilter( 'staffmember_id', array('eq'=> $staffmember_id));
                    }else{
                        $booking_collection->addFieldToFilter( 'product_id', array('eq'=> $product_id));
                    }
                    
                    
                    $booking_data = $booking_collection->getData();
                    
                    
                    //helper function to compare the current selected time with the db reserve times
                    
                    if(!Mage::helper('bookingreservation')->isCurrentSelectedTimeIsFree($booking_data,$from_time,$to_time)){
                        
                        Mage::app()->getResponse()->setRedirect($product->getProductUrl());   
                        Mage::app()->getResponse()->sendResponse();
                        
                        Mage::getSingleton('core/session')->addNotice(Mage::helper('bookingreservation')->__('Sorry, Selected Time of the day is booked already'));                        
                        exit;
                    }
                    
            }
	    
    }
    
    
    
    
    
        
    public function updatePriceAddToCartAfter(Varien_Event_Observer $obs)
    {
    
               
            // Get the quote item
            $item = $obs->getQuoteItem();
            // Ensure we have the parent item, if it has one
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            
	    
            //hidden filed from detail page
            if($_POST['product-booking-price']){
            
                $item->setCustomPrice($_POST['product-booking-price']);
                $item->setOriginalCustomPrice($_POST['product-booking-price']);
                $item->getProduct()->setIsSuperMode(true);
                
            }
            
            $booking_date = '';
            $from_time = '';
            $to_time = '';
            $staff_member_name = '';
            
            
            
	    
	    if($item->getProduct()->getIsFullDayBooking()){
			    
			    $booked_days = $_POST['booking-selected-days'];
			    
			    $additionalOptions = array();
			    if ($additionalOption = $item->getCustomOption('additional_options'))
			    {
				$additionalOptions = (array) unserialize($additionalOption->getValue());
			    }
			    
			    $additionalOptions[] = array(
							    'label' => 'Selected Days',
							    'value' => $booked_days,
							);
			    
			    $item->addOption(array(
				    'code' => 'additional_options',
				    'value' => serialize($additionalOptions)
			    ));
			    
		    
	    }else{
		    
		    if($_POST){
               
			$booking_date = $_POST['fme_calendar_date'];
			
			$from_time = $_POST['fme_booking_time_from']['hours'].':'.$_POST['fme_booking_time_from']['minutes'].' '.$_POST['fme_booking_time_from']['daypart']; 
			 
			 $to_time = $_POST['fme_booking_time_to']['hours'].':'.$_POST['fme_booking_time_to']['minutes'].' '.$_POST['fme_booking_time_to']['daypart']; 
			 
			 
			 $staff_member_id = $_POST['booking_staff_members'];
			 
			 if($staff_member_id != 0){
			     
			     $staf_model = Mage::getModel('bookingreservation/staffmembers')->load($staff_member_id);
			     $staff_member_name = $staf_model->getMemberName();
			 }
			 
		    }
		    
		    if($booking_date && $from_time && $to_time){            
            
			    // assuming you are posting your custom form values in an array called extra_options...
			    
			    // add to the additional options array
			    $additionalOptions = array();
			    if ($additionalOption = $item->getCustomOption('additional_options'))
			    {
				$additionalOptions = (array) unserialize($additionalOption->getValue());
			    }
			    
			    $additionalOptions[] = array(
							    'label' => 'Date',
							    'value' => $booking_date,
							);
				    
			    $additionalOptions[] = array(
							    'label' => 'From',
							    'value' => $from_time,
							);
			    
			    $additionalOptions[] = array(
							    'label' => 'To',
							    'value' => $to_time,
							);
			    
			    if($staff_member_name != ''){
				
			    $additionalOptions[] = array(
							    'label' => 'With',
							    'value' => $staff_member_name,
							);
			    }
			    
			    
			    $item->addOption(array(
				    'code' => 'additional_options',
				    'value' => serialize($additionalOptions)
			    ));
			    
		    }
		    
	    } 
            
            
            
    
    }
    
    
    
    public function cartUpdateProductWithOptions(Varien_Event_Observer $obs){
	
	
	    // Get the quote item
            $item = $obs->getQuoteItem();
            // Ensure we have the parent item, if it has one
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            
	    
	    
	    //hidden filed from detail page
            if($_POST['product-booking-price']){
            
                $item->setCustomPrice($_POST['product-booking-price']);
                $item->setOriginalCustomPrice($_POST['product-booking-price']);
                $item->getProduct()->setIsSuperMode(true);
		
            }
	    
	    
	    if($item->getProduct()->getIsFullDayBooking()){
			    
			    $booked_days = $_POST['booking-selected-days'];
			    
			    $additionalOptions = array();
			    if ($additionalOption = $item->getCustomOption('additional_options'))
			    {
				$additionalOptions = (array) unserialize($additionalOption->getValue());
			    }
			    
			    $additionalOptions[] = array(
							    'label' => 'Selected Days',
							    'value' => $booked_days,
							);
			    
			    $item->addOption(array(
				    'code' => 'additional_options',
				    'value' => serialize($additionalOptions)
			    ));
			    
		    
	    }else{
		    
		    if($_POST){
               
			$booking_date = $_POST['fme_calendar_date'];
			
			$from_time = $_POST['fme_booking_time_from']['hours'].':'.$_POST['fme_booking_time_from']['minutes'].' '.$_POST['fme_booking_time_from']['daypart']; 
			 
			 $to_time = $_POST['fme_booking_time_to']['hours'].':'.$_POST['fme_booking_time_to']['minutes'].' '.$_POST['fme_booking_time_to']['daypart']; 
			 
			 
			 $staff_member_id = $_POST['booking_staff_members'];
			 
			 if($staff_member_id != 0){
			     
			     $staf_model = Mage::getModel('bookingreservation/staffmembers')->load($staff_member_id);
			     $staff_member_name = $staf_model->getMemberName();
			 }
			 
		    }
		    
		    if($booking_date && $from_time && $to_time){            
            
			    // assuming you are posting your custom form values in an array called extra_options...
			    
			    // add to the additional options array
			    $additionalOptions = array();
			    if ($additionalOption = $item->getCustomOption('additional_options'))
			    {
				$additionalOptions = (array) unserialize($additionalOption->getValue());
			    }
			    
			    $additionalOptions[] = array(
							    'label' => 'Date',
							    'value' => $booking_date,
							);
			    
			    $additionalOptions[] = array(
							    'label' => 'From',
							    'value' => $from_time,
							);
			    
			    $additionalOptions[] = array(
							    'label' => 'To',
							    'value' => $to_time,
							);
			    
			    if($staff_member_name != ''){
				
			    $additionalOptions[] = array(
							    'label' => 'With',
							    'value' => $staff_member_name,
							);
			    }
			    
			    
			    $item->addOption(array(
				    'code' => 'additional_options',
				    'value' => serialize($additionalOptions)
			    ));
			    
		    }
		    
	    }
	    
	    
    }
    
    
    
    
    
    
    
        
    public function saveBookingInfoFromOrder(Varien_Event_Observer $observer){
        
            $_info_required = null;
            
            $order = $observer->getEvent()->getOrder();
            
            //$last_orderid = $order->getIncrementId();
            
            $last_orderid = $order->getId();
            $order_status = $order->getStatus();
            
            
            //$order_data = Mage::getModel('sales/order')->load($order->getId())->getData();            
            //$order_status = $order_data['status'];
            
            
            if (self::$_counterIncrement > 1) {
                return $this;
            }
            
            self::$_counterIncrement++;
            
            
            $items = $order->getAllItems();

            $customer_id = $order->getCustomerId();
            
            foreach ($items as $itemId => $item){
                    
                        $store_id = Mage::app()->getStore()->getStoreId();
                        $product_id = $item->getProductId();
                        $opts_info = $item->getProductOptions();
                        
                        $_newProduct = Mage::getModel('catalog/product')->load($product_id); 
                        
                        
                        if($_newProduct->getIsBookingProduct()){
			    
			    
			    if($_newProduct->getIsFullDayBooking()){ // day booking
			    
				    $booking_buy_req = $opts_info['info_buyRequest'];
				    
				    $selected_days = $booking_buy_req['booking-selected-days'];
				    
				    $model = Mage::getModel('bookingreservation/bookingreservation');
				    $model->addData( array(
				    
							'booking_order_id' => $last_orderid,
							'product_id' => $product_id,
							'store_id' => $store_id,  
							'reserve_options' => serialize($opts_info),
							'customer_id'   =>  $customer_id,
							'status'   =>  $order_status,
							'created_time' => now(),
							'update_time' => now(),
							'days_booking' => $selected_days
						    )
					    );                                
				    $model->save();
								
								/* sending email to staff ... watch out!*/
								$_info_required = array(
									'customer_id'   =>  $customer_id,
									'staffmember_id'   =>  '',
									'product_id' => $product_id,
									'reserve_from_time'   =>  '',
									'reserve_to_time'   =>  '',
									'status' => $order_status,
									'reserve_day'=>$selected_days
								);
								
				    
			    }else{ // time booking
				    
				    $booking_buy_req = $opts_info['info_buyRequest'];
				    
				    $current_day = $booking_buy_req['fme_calendar_date'];
				    $staffmember_id = $booking_buy_req['booking_staff_members'];
				    
				    $from_time = $booking_buy_req['fme_booking_time_from']['hours'].':'.$booking_buy_req['fme_booking_time_from']['minutes'].' '.$booking_buy_req['fme_booking_time_from']['daypart'];
				    $to_time = $booking_buy_req['fme_booking_time_to']['hours'].':'.$booking_buy_req['fme_booking_time_to']['minutes'].' '.$booking_buy_req['fme_booking_time_to']['daypart'];
				    
				    $buffer_period = $booking_buy_req['product-booking-buffer-period'];
				    
				    //add buffer time before saving data - to include in the reserve time for frontend time_schedule
				    $orig_to_minutes = $booking_buy_req['fme_booking_time_to']['minutes'];
				    $orig_to_hrs = $booking_buy_req['fme_booking_time_to']['hours'];
				    $orig_to_dayp = $booking_buy_req['fme_booking_time_to']['daypart'];
				    
				    $bufer_to_minutes = $orig_to_minutes+$buffer_period;
				    if($bufer_to_minutes >= 60){
					
					if($orig_to_hrs == 12){
					    $orig_to_hrs = 1;
					    $orig_to_dayp = 'pm';
					    $bufer_to_minutes = $bufer_to_minutes-60; 
					}else{
					    $orig_to_hrs = $orig_to_hrs+1;
					    $bufer_to_minutes = $bufer_to_minutes-60;
					    if($orig_to_hrs==12){ $orig_to_dayp = 'pm'; }
					}                                
					
				    }
				    
				    if($orig_to_hrs <= 9){
					
					$orig_to_hrs = '0'.(int) $orig_to_hrs;
				    }                            
				    if($bufer_to_minutes <= 9){
					
					$bufer_to_minutes = '0'.(int) $bufer_to_minutes;
				    }
				    
				    $bufer_to_time = $orig_to_hrs.':'.$bufer_to_minutes.' '.$orig_to_dayp;
				    
				    
				    $model = Mage::getModel('bookingreservation/bookingreservation');
				    $model->addData( array(
				    
							'booking_order_id' => $last_orderid,
							'product_id' => $product_id,
							'store_id' => $store_id,  
							'reserve_options' => serialize($opts_info),
							'reserve_day'   =>  $current_day,
							'reserve_from_time'   =>  $from_time,
							'reserve_to_time'   =>  $bufer_to_time,
							'buffer_period'   =>  $buffer_period,
							'customer_id'   =>  $customer_id,
							'staffmember_id'   =>  $staffmember_id,
							'status'   =>  $order_status,
							'created_time' => now(),
							'update_time' => now()
						    )
					    );                                
				    $model->save();
								
								/* sending email to staff ... watch out!*/
								$_info_required = array(
									'customer_id'   =>  $customer_id,
									'staffmember_id'   =>  $staffmember_id,
									'product_id' => $product_id,
									'reserve_from_time'   =>  $from_time,
									'reserve_to_time'   =>  $to_time,
									'status' => $order_status,
									'reserve_day'=>$current_day
								);
								
			    }
			    
                        }//if is booking product                        
            
            }
            
            Mage::helper('bookingreservation')->email_to_staff($_info_required);
            return $this;
        
    }
    
    
    
    
    public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer)
    {
        
        $quoteItem = $observer->getItem();
        if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')) {
            $orderItem = $observer->getOrderItem();
            $options = $orderItem->getProductOptions();
            $options['additional_options'] = unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }
        
    }


    public function changeBookingStatusByOrderStatusChange($observer){
        
        
        $order = $observer->getEvent()->getOrder();
        
        $orderid = $order->getId();
        
        $orderstatus = $order->getStatus();
        
        //load bookings having this order id
        
            //$store_id = Mage::app()->getStore()->getStoreId();           
            
            $resource = Mage::getSingleton('core/resource');
            $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
            $booking_table = $resource->getTableName('bookingreservation');            
            
            $connection->beginTransaction();
            
            $fields = array();
            $fields['status'] = $orderstatus;
            
            $where = $connection->quoteInto('booking_order_id =? AND ', $orderid).$connection->quoteInto('status !=(?)', 'canceled');
            
            $connection->update($booking_table, $fields, $where);
            
            $connection->commit();          
            
            
    }
    
    
    
    public function saveCustomerDistanceFromStore($observer){
        
        
            $address_obj = $observer->getCustomerAddress();
            $c_address = $address_obj->getData();
            
            $customer_country = Mage::getModel('directory/country')->loadByCode($address_obj->getCountry());            
            $customer_address = $c_address['street'].' '.$c_address['city'].', '.$c_address['region'].', '.$c_address['postcode'].' '.$customer_country->getName();
            $customer_address_url =  urlencode($customer_address);
            
            
            $merchant_country = Mage::getModel('directory/country')->loadByCode(Mage::getStoreConfig('general/store_information/merchant_country'));
            $merchant_address = Mage::getStoreConfig('general/store_information/address').' '.$merchant_country->getName();
            $merchant_address_url =  urlencode($merchant_address);
           
           
            $map_url = "http://maps.google.com/maps/api/directions/xml?origin=".$merchant_address_url."&destination=".$customer_address_url."&sensor=false";
            
        
            $response_xml_data = simplexml_load_file($map_url);
            
            $calculated_distance = '';
            $calculated_duration = '';
            
            
            if($response_xml_data){                   
                
                if($response_xml_data->status == 'OK'){
                    
                  $distance_obj = $response_xml_data->route->leg->distance;
                  $duration_time_obj = $response_xml_data->route->leg->duration;
                  
                  $calculated_distance = $distance_obj->text;
                  $calculated_duration = $duration_time_obj->text;        
                  
                }        
            
            }
            
            
            //now save this distance and duration againt customer            
            
            //echo 'DDDDD'.$calculated_distance;        
            //exit; 
    
    }
    
        public function invoiceSaveAfter(Varien_Event_Observer $observer)
	{
		$invoice = $observer->getEvent()->getInvoice();
		if ($invoice->getBaseHomeserviceAmount()) {
			$order = $invoice->getOrder();
			$order->setHomeserviceAmountInvoiced($order->getHomeserviceAmountInvoiced() + $invoice->getHomeserviceAmount());
			$order->setBaseHomeserviceAmountInvoiced($order->getBaseHomeserviceAmountInvoiced() + $invoice->getBaseHomeserviceAmount());
		}
		return $this;
	}
        
	public function creditmemoSaveAfter(Varien_Event_Observer $observer)
	{
		/* @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
		$creditmemo = $observer->getEvent()->getCreditmemo();
		if ($creditmemo->getHomeserviceAmount()) {
			$order = $creditmemo->getOrder();
			$order->setHomeserviceAmountRefunded($order->getHomeserviceAmountRefunded() + $creditmemo->getHomeserviceAmount());
			$order->setBaseHomeserviceAmountRefunded($order->getBaseHomeserviceAmountRefunded() + $creditmemo->getBaseHomeserviceAmount());
		}
		return $this;
	}
    
        
        
        
        public function sendBookingSummaryEmail()
	{
            
            
            if(Mage::helper('bookingreservation')->isBookingSummaryEnabled()):
            
                    
                    $today = date('Y-m-d');
                    $next_day = date('Y-m-d', strtotime('+ 6 month',strtotime(date('Y-m-d'))));
                    
                    
                    
                    $booking_collection = Mage::getModel('bookingreservation/bookingreservation')->getCollection();
                    
                    $booking_collection->addFieldToFilter('reserve_day', array(
                        'from' => $today,
                        'to' => $next_day,
                        'date' => true, // specifies conversion of comparison values
                    ));               
                    
                    $booking_data = $booking_collection->getData();
                    
                    
                    
                    $product_model      = Mage::getModel('catalog/product');
                    $staffmember_model  = Mage::getModel('bookingreservation/staffmembers');
                    $customer_model     = Mage::getModel('customer/customer');                    
                    
                    
                    $html = '
                        <body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
                        <div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
                        <table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
                                <tr>
                        <td valign="top"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'">
                        <img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/default/default/images/logo_email.gif"  style="margin-bottom:10px;" border="0"/></a></td>
                        </tr>';
                        
                        
                        foreach($booking_data as $b_data){
                            
                            $product = $product_model->load($b_data['product_id']);
                            $customer = $customer_model->load($b_data['customer_id']);
                            $staffmember  = $staffmember_model->load($b_data['staffmember_id']);
                            
                            
                                $html.= '
                                        <tr>
                                                <td><b>Product Name</b></td>
                                                <td>'.$product->getName().'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Product Url</b></td>
                                                <td>'.Mage::getBaseUrl().$product->getUrlPath().'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Customer Name</b></td>
                                                <td>'.$customer->getName().'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Customer Email</b></td>
                                                <td>'.$customer->getEmail().'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Staff Member</b></td>
                                                <td>'.$staffmember->getMemberName().'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Reserved From</b></td>
                                                <td>'.$b_data['reserve_from_time'].'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Reserved To</b></td>
                                                <td>'.$b_data['reserve_to_time'].'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Reserved Day</b></td>
                                                <td>'.$b_data['reserve_day'].'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Status</b></td>
                                                <td>'.$b_data['status'].'</td>
                                        </tr>
                                        <tr>
                                                <td><b>Order Date/Time</b></td>
                                                <td>'.$b_data['created_time'].'</td>
                                        </tr>
                                        <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                        </tr>';
                                        
                        }
                        
                        
                                
                    $html.= '
                        </table>
                        </div>
                        </body>';
                        
                        
                    // send this summary to owner/admin
                    
                    $owner_name = Mage::getStoreConfig('trans_email/ident_general/name');
                    $owner_email = Mage::getStoreConfig('trans_email/ident_general/email');
                    
                    
                    //send email from sales depertment
                    $sales_name = Mage::getStoreConfig('trans_email/ident_sales/name');
                    $sales_email = Mage::getStoreConfig('trans_email/ident_sales/email');
                    
                    
                    
                        $mail = new Zend_Mail();
                        $mail->setBodyHtml($html)
                        ->setFrom($sales_name,$sales_email)
                        ->addTo($owner_email,$owner_name);
                        
                        //->addCc('asif.hussain@unitedsol.net', 'AsifCC');
                        
                        
                        // send this summary to enabled staffmembers
                        $member_collection = $staffmember_model->getCollection();
                        foreach($member_collection as $member_data){
                            
                            if($member_data->getStatus()){ // if member enable
                                
                                $mail->addCc($member_data->getStaffEmail(), $member_data->getMemberName());
                                
                            }
                            
                            
                        }
                        
                        
                        
                        
                        $mail->setSubject("Booking & Reservation Summary");
                        
                        try
                        {
                                $mail->send();
                                
                        }
                        catch (Exception $exception)
                        {
                                
                        }
            
            endif;
            
            
        }
	
	
	
	public function addBRCustomOptions($observer)
	{
	    
	    
	    $product = $observer->getProduct();
	    
	    
	    
	    if($product->getIsBookingProduct()) {
		
		$already_exist = false;
		
		//check whether, 'timeslot' option already exist
		foreach($product->getOptions() as $op){
		    
		    if($op->getTitle() == 'timeslot'){
			
			$already_exist = true;
			
		    }
		    
		}
		
		//if not exist then create option for booking_product
		if(!$already_exist && $product->getId() != ''){
					    
			    $values = array(
					array(
					    'title'		=> '15min',
					    'price'		=> 0,
					    'price_type'	=> 'fixed',
					    'sort_order'	=> 0,
					)
				);
			    
			    $options = 	array(
				    'previous_type' 	=> 'drop_down',
				    'previous_group'	=> 'select',
				    'title' 		=> 'timeslot',
				    'type'			=> 'drop_down',
				    'is_require'		=> 0,
				    'sort_order'		=> 0,
				    'price_type'		=> 'fixed',
				    'values'		=> $values
				);
			    
			    
			    $opt = Mage::getModel('catalog/product_option');
			    $opt->setProduct($product);
			    
			    $product->setHasOptions(1);
			    $opt->addOption($options);
			    $opt->saveOptions();
			    $product->addOption($opt);
			    
		}
		
				
	    }
	    
	    
	
	}
	
	
	
	public function setProductPageTemplate($observer){
	    
	    $layout = $observer->getEvent()->getLayout();
	    
	    
	    if($observer->getAction()->getFullActionName() == 'catalog_product_view')
	    {	
		$_params = $observer->getEvent()->getAction()->getRequest()->getParams();
		$prod_id = @$_params['id'];
		
		if($prod_id){
		    
		    $product = Mage::getModel('catalog/product')->load($prod_id);
		    
		    if($product->getIsBookingProduct()){
			
			$layout->getUpdate()->addUpdate('<reference name="product.info">
							<action method="setTemplate"><template>bookingreservation/catalog/product/view.phtml</template></action>
							<block type="catalog/product_view" name="booking_options_js" as="booking_options_js" template="bookingreservation/catalog/product/view/options/js/booking_options_js.phtml"/>
							<block type="catalog/product_view" name="booking_day_options_js" as="booking_day_options_js" template="bookingreservation/catalog/product/view/options/js/booking_day_options_js.phtml"/>							
							<reference name="product.info.options.wrapper">
							    <reference name="product.info.options">
								<action method="addOptionRenderer"><type>select</type><block>bookingreservation/product_view_options_type_select</block><template>bookingreservation/catalog/product/view/options/type/select.phtml</template></action>
							    </reference>
							</reference>							
							</reference>');
			//then generate the xml
			$layout->generateXml();    		    
			
		    }    
		    
		}
		
		
	    }else //edit product from cart page link
	    if( $observer->getAction()->getFullActionName() == 'checkout_cart_configure' ) 
	    {	
		$_params = $observer->getEvent()->getAction()->getRequest()->getParams();
		$quote_item_id = @$_params['id'];
		
		if($quote_item_id){
		    
		    $item = Mage::getSingleton('checkout/session')->getQuote()->getItemById($quote_item_id);
		    $product_id = $item->getProductId();
		    
		    //echo "<pre>"; print_r($product_id); exit;		    
		    $product = Mage::getModel('catalog/product')->load($product_id);
		    
		    if($product->getIsBookingProduct()){
			
			$layout->getUpdate()->addUpdate('<reference name="product.info">
							<action method="setTemplate"><template>bookingreservation/catalog/product/view.phtml</template></action>
							<block type="catalog/product_view" name="booking_options_js" as="booking_options_js" template="bookingreservation/catalog/product/view/options/js/booking_options_js.phtml"/>
							<block type="catalog/product_view" name="booking_day_options_js" as="booking_day_options_js" template="bookingreservation/catalog/product/view/options/js/booking_day_options_js.phtml"/>							
							<reference name="product.info.options.wrapper">
							    <reference name="product.info.options">
								<action method="addOptionRenderer"><type>select</type><block>bookingreservation/product_view_options_type_select</block><template>bookingreservation/catalog/product/view/options/type/select.phtml</template></action>
							    </reference>
							</reference>							
							</reference>');
			//then generate the xml
			$layout->generateXml();    		    
			
		    }    
		    
		}
		
		
	    }
	    
	    
	    
	    
	}
	
    
    
    
}
