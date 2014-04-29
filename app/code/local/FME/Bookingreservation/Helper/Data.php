<?php

class FME_Bookingreservation_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    
    
    const CALENDAR_WIDTH             =       'bookingreservation/calendar_settings/calendar_width';
    const CALENDAR_HEIGHT            =       'bookingreservation/calendar_settings/calendar_height';
    const CALENDAR_BG_COLOR          =       'bookingreservation/calendar_settings/calendar_bg_color';
    const CALENDAR_BORDER            =       'bookingreservation/calendar_settings/calendar_border';
    const CALENDAR_FONT              =       'bookingreservation/calendar_settings/calendar_font';
    const CALENDAR_HEAD_BG           =       'bookingreservation/calendar_settings/calendar_head_bg';
    const CALENDAR_HEAD_FONT         =       'bookingreservation/calendar_settings/calendar_head_font';
    const CALENDAR_WEEK_DAYS_BG      =       'bookingreservation/calendar_settings/calendar_head_week_bg';
    const CALENDAR_CURRENT_DAY_COLOR =       'bookingreservation/calendar_settings/calendar_current_day_color';
    const CALENDAR_SELECTED_DAY_BG   =       'bookingreservation/calendar_settings/calendar_selected_day_bg';
    const CALENDAR_PAST_DAY_BG       =       'bookingreservation/calendar_settings/calendar_past_day_bg';
    const CALENDAR_MOUSEOVER_DAY_BG  =       'bookingreservation/calendar_settings/calendar_hover_day_bg';
    
    const SCHEDULAR_FREE_COLOR       =       'bookingreservation/scheduler_settings/free_time_color';
    const SCHEDULAR_BUSY_COLOR       =       'bookingreservation/scheduler_settings/busy_time_color';
    const SCHEDULAR_BACKGROUND_COLOR =       'bookingreservation/scheduler_settings/head_bg';
    const SCHEDULAR_FONT             =       'bookingreservation/scheduler_settings/font';
    
    const HOMESERVICE_DISTANCE_CHARGES_MILE     =       'bookingreservation/home_service/distance_charges';
    const HOMESERVICE_DISTANCE_CHARGES_KM       =       'bookingreservation/home_service/km_distance_charges';    
    const HOMESERVICE_CANCEL_CHARGES            =       'bookingreservation/home_service/cancel_charges';
    
    const STAFF_EMAIL_SUBJECT = 'bookingreservation/reply/subject';
    const STAFF_MEMBERS_ENABLED = 'bookingreservation/reply/member_enable';
    
    const BOOKING_SUMMARY_ENABLED = 'bookingreservation/booking_summary/enabled';
    
    
    
    public function isStaffMembersEnabled(){
        
        return Mage::getStoreConfig(self::STAFF_MEMBERS_ENABLED);            
       
    }
    
    
    
    public function isBookingSummaryEnabled(){
        
        return Mage::getStoreConfig(self::BOOKING_SUMMARY_ENABLED);            
       
    }
    
    
    public function get_staff_email_subject()
    {
		$subject = Mage::getStoreConfig(self::STAFF_EMAIL_SUBJECT);
		
		if ($subject == '')
		{
			$subject = 'Email Notification';
		}
		
		return $subject;
	}
	
    public function getCalendarWidth(){
        
        if(Mage::getStoreConfig(self::CALENDAR_WIDTH) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_WIDTH);            
        }else{            
            return '180px';
        }
    }
    
    public function getCalendarHeight(){
        
        if(Mage::getStoreConfig(self::CALENDAR_HEIGHT) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_HEIGHT);            
        }else{            
            return '200px';
        }
    }
    
    public function getCalendarBackgroundColor(){
        
        if(Mage::getStoreConfig(self::CALENDAR_BG_COLOR) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_BG_COLOR);            
        }else{            
            return '#EEEEEE';
        }
    }
    
    public function getCalendarBorder(){
        
        if(Mage::getStoreConfig(self::CALENDAR_BORDER) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_BORDER);            
        }else{            
            return '1px solid #AAAAAA';
        }
    }
    
    public function getCalendarFont(){
        
        if(Mage::getStoreConfig(self::CALENDAR_FONT) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_FONT);            
        }else{            
            return '11px/20px Helvetica';
        }
    }
    
    public function getCalendarHeadBackground(){
        
        if(Mage::getStoreConfig(self::CALENDAR_HEAD_BG) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_HEAD_BG);            
        }else{            
            return '#FFFFFF';
        }
    }
    
    public function getCalendarHeadFont(){
        
        if(Mage::getStoreConfig(self::CALENDAR_HEAD_FONT) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_HEAD_FONT);            
        }else{            
            return '11px/20px Helvetica';
        }
    }
    
    public function getCalendarWeekDaysBg(){
        
        if(Mage::getStoreConfig(self::CALENDAR_WEEK_DAYS_BG) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_WEEK_DAYS_BG);            
        }else{            
            return '#DDDDDD';
        }
    }
    
    public function getCurrentDayColor(){
        
        if(Mage::getStoreConfig(self::CALENDAR_CURRENT_DAY_COLOR) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_CURRENT_DAY_COLOR);            
        }else{            
            return '#D50000';
        }
    }
    
    public function getSelectedDayBg(){
        
        if(Mage::getStoreConfig(self::CALENDAR_SELECTED_DAY_BG) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_SELECTED_DAY_BG);            
        }else{            
            return '#FFFFFF';
        }
    }
    
    
    public function getPastDayBg(){
        
        if(Mage::getStoreConfig(self::CALENDAR_PAST_DAY_BG) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_PAST_DAY_BG);            
        }else{            
            return '#D0D0D0';
        }
    }
    
    
    public function getMouseoverDayBg(){
        
        if(Mage::getStoreConfig(self::CALENDAR_MOUSEOVER_DAY_BG) != ''){            
            return Mage::getStoreConfig(self::CALENDAR_MOUSEOVER_DAY_BG);            
        }else{            
            return '#34ABFA';
        }
    }
    
    
    public function getSchedularFreeColor(){
        
        if(Mage::getStoreConfig(self::SCHEDULAR_FREE_COLOR) != ''){            
            return Mage::getStoreConfig(self::SCHEDULAR_FREE_COLOR);            
        }else{            
            return '#D0F2AB';
        }
    }
    
    
    public function getSchedularBusyColor(){
        
        if(Mage::getStoreConfig(self::SCHEDULAR_BUSY_COLOR) != ''){            
            return Mage::getStoreConfig(self::SCHEDULAR_BUSY_COLOR);            
        }else{            
            return '#F73F0C';
        }
    }
    
    public function getSchedularBackgroundColor(){
        
        if(Mage::getStoreConfig(self::SCHEDULAR_BACKGROUND_COLOR) != ''){            
            return Mage::getStoreConfig(self::SCHEDULAR_BACKGROUND_COLOR);            
        }else{            
            return '#DDDDDD';
        }
    }
    
    public function getSchedularFont(){
        
        if(Mage::getStoreConfig(self::SCHEDULAR_FONT) != ''){            
            return Mage::getStoreConfig(self::SCHEDULAR_FONT);            
        }else{            
            return '13px/20px Helvetica';
        }
    }
    
    
    public function getBookingCancelCharges(){
        
        $charges = 0;
        
        if(Mage::getStoreConfig(self::HOMESERVICE_CANCEL_CHARGES) != ''){            
            $charges = Mage::getStoreConfig(self::HOMESERVICE_CANCEL_CHARGES);
            $charges = substr($charges,1,strlen($charges));            
        }
        return $charges;        
    }
    
    public function getOrignalBookingCancelCharges(){
        
        $charges = 0;
        
        if(Mage::getStoreConfig(self::HOMESERVICE_CANCEL_CHARGES) != ''){            
            $charges = Mage::getStoreConfig(self::HOMESERVICE_CANCEL_CHARGES);                        
        }
        return $charges;        
    }
    
    
    //not using anywhare
    public function customerAddressForBookingsHtml($customer){
        
        //Asad hhh, 550 Madison Avenue2, New York, New York 10001, United States        
        
        $address_html = '<select onchange="addHomeServiceCharges()" title="" class="address-select" id="fme-booking-address-select" name="booking_address_id">';
        $address_html = $address_html.'<option selected="selected" value="">'.$this->__('Select Address').'</option>';
        
        foreach ($customer->getAddresses() as $address) {
            
            $customer_data = $address->getData();
            $c_address_label = '';
            $c_address_value = '';
            
            $customer_country = Mage::getModel('directory/country')->loadByCode($address->getCountry());
            if($address->getName()){
                $c_address_label =  $address->getName();
            }
            if($customer_data['street']){
                $c_address_label = $c_address_label.', '.$customer_data['street'];
                $c_address_value = $customer_data['street'];
            }
            if($address->getRegion()){
                $c_address_label = $c_address_label.', '.$address->getRegion();
                $c_address_value = $c_address_value.', '.$address->getRegion();
            }
            if($address->getCity()){
                $c_address_label = $c_address_label.', '.$address->getCity();
                $c_address_value = $c_address_value.', '.$address->getCity();
            }
            if($address->getPostcode()){
                $c_address_label = $c_address_label.' '.$address->getPostcode();
                $c_address_value = $c_address_value.' '.$address->getPostcode();
            }
            if($customer_country->getName()){
                $c_address_label = $c_address_label.', '.$customer_country->getName();
                $c_address_value = $c_address_value.', '.$customer_country->getName();
            }
            
            $address_html = $address_html.'<option value="'.$c_address_value.'">'.$c_address_label.'</option>';
            
        }
        
        $address_html = $address_html.'</select>';
        
        echo $address_html;        
        
    }
    
    
    
    
    
    
    
    // check whether the user's selected time is reserve/free (against that product, and that day) dbtime
    
    function isCurrentSelectedTimeIsFree($dbtime,$from_time,$to_time){
        
        if($dbtime){
            
            $is_available = true;
            
            foreach($dbtime as $busy_time){
                
                
                if(
                            ($this->conv($busy_time['reserve_from_time'])  >=  $this->conv($from_time)   &&     $this->conv($busy_time['reserve_from_time'])  <= $this->conv($to_time))
                            || ($this->conv($busy_time['reserve_to_time'])  >=  $this->conv($from_time)  &&     $this->conv($busy_time['reserve_to_time'])  <= $this->conv($to_time))
                            
                            //|| ($this->conv($busy_time['reserve_from_time'])  <=  $this->conv($from_time)     &&     $this->conv($busy_time['reserve_to_time'])  >= $this->conv($to_time))         
                            
                            
                            ){
                    
                        //user selected time, or its part is booked already                        
                        $is_available = false;
                    
                }           
                
            }
            
            return $is_available;
            
        }       
        
        return true;                
        
    }  
    
    
    public function isCurrentSelectedDaysAreFree($booking_days, $product){
	    
	    $prod_id = $product->getId();
	    $store_id = Mage::app()->getStore()->getStoreId();
	    
	    $model = Mage::getModel('bookingreservation/bookingreservation');
                    
	    $booking_collection = $model->getCollection()
					->addFieldToFilter( 'days_booking', array('neq'=> ''))
                                        ->addFieldToFilter( 'product_id', array('eq'=> $prod_id))
                                        ->addFieldToFilter( 'store_id', array('eq'=> $store_id))
					->addFieldToFilter( 'status', array('in'=> array('processing','complete')));
	    
	    $booking_data = $booking_collection->getData();
	    
	    $booking_days_arr = explode(',',$booking_days);
	    $reserved_days = array();
	    
	    
	    foreach($booking_days_arr as $ar_data){
		
		//$ar_data is 2013-04-27
		foreach($booking_data as $db_data){
		    
		    $db_days_arry = explode(',', $db_data['days_booking']);
		    if(in_array($ar_data,$db_days_arry)){ // if that day exist in db
			
			$reserved_days[] = $ar_data;
		    }
		    
		}
		
	    }
	    
	    if($reserved_days){
		return $reserved_days;
	    }
	    
	    return false;
    }
    
    
    
    // Function to convert time 24 hour mode and convert it to integer.
    // ie $time = 11:45 am
    
    function conv($time){
        
            //get am pm
            $ampm=explode(" ",$time);
            //get H:M
            $hms=explode(":",$ampm[0]);
            
            //figure out noon vs midnight, convert to 24 hour time.
            if($hms[0]==12 && $ampm[1]=="am"){
                $hms[0]="00";
            }
            else{
               if($ampm[1]=="pm"){
                  if($hms[0]<12){
                    $hms[0]=$hms[0]+12;
                  }
               }
            }
            
            //build the integer
            $build=$hms[0].$hms[1];
            
            return $build;
    }
    
    
    function toConv($str_time){
        
	if(strlen($str_time) == 3){
	    
	    $hrs = substr($str_time,0,1);
	    $min = substr($str_time,1,3);
	    
	}else{
	    
	    $hrs = substr($str_time,0,2);
	    $min = substr($str_time,2,4);
	}
	
	
        $daypart = 'am';
        
        if($min >= 60){            
            $hrs = $hrs+1;
            $min = $min-60;
        }
        
        if($hrs == 12){
            $daypart = 'pm';
        }
        
        if($hrs > 12){            
            $daypart = 'pm';            
            $hrs = $hrs-12;            
        }
        
        if($hrs <= 9){
            $hrs = '0'.$hrs;
        }
        
        if($min <= 9 && strlen($min) <= 1){
            $min = '0'.$min;
        }
        
        return $hrs.':'.$min.' '.$daypart;
        
        
    }
    
    
    
    // booking cancelation time, before appointment
    
    public function canCancelTheBooking($booking_order_id,$prod_id,$booking_app_day,$booking_app_time){
        
        $order_model = Mage::getModel('sales/order');        
        $order = $order_model->load($booking_order_id);
        
        //echo "<pre>"; print_r($order->getData()); exit;
        
        if(!$order->canCancel()){
            
            //return false;
        
        }
        
        
        $prod_model = Mage::getModel('catalog/product');
        
        $product = $prod_model->load($prod_id);
        
        $cancel_time = $product->getBookingCancelTime();
        
        $cancel_time = (float) $cancel_time;
        
        $cancel_time_minutes = $cancel_time*60;
        
           
        if(strtotime(now('l')) == strtotime($booking_app_day)){ // the day where appointment time can be, and he cant cancel it 
            
            //todays time by adding cancel time            
            $todays_c_time = date("h:i a", strtotime('+'.$cancel_time_minutes.' minutes'));
            
            //suppose 120 mnt before appointment
            $app_condition_time = date('h:i a',strtotime('-'.$cancel_time_minutes.' minutes',strtotime($booking_app_time)));
            
            
            // if this todays time increase the appointment time then he will can not cancel the booking
            //if(strtotime($todays_c_time) > strtotime($booking_app_time)){
            //    
            //    return false;                
            //    
            //}else{
            //    //still have time to cancel booking
            //    return true;
            //}
            
            if( (strtotime(date('h:i a')) >= strtotime($app_condition_time)) && (strtotime(date('h:i a')) <= strtotime($booking_app_time)) ){
                
                
               return 'charge_cancel'; 
                
            }else
            if(strtotime(date('h:i a')) < strtotime($app_condition_time)){
                
               return 'free_cancel';  
            }
            else
            if(strtotime(date('h:i a')) > strtotime($booking_app_time)){
                
               return 'expired_cancel';  
            }
            
            
        }else
        if(strtotime(now('l')) > strtotime($booking_app_day)){ // appointemnt day has been passed
            
            return 'expired_cancel';  
            
        }
        else
        if(strtotime(now('l')) < strtotime($booking_app_day)){ // appointemnt day has to come next
            
            return 'free_cancel';
            
        }
        
        
        //echo "<pre>"; print_r($product);
        //exit; 
        
    }
    
    
    
    public function getCustomerBookingAddressDistance($customer_address){
	
	    
	    if($customer_address != ''){
		
		    $customer_address_url =  urlencode($customer_address);
		      
		    
		    if(Mage::getStoreConfig('general/store_information/address')){
			
			//$merchant_country = Mage::getModel('directory/country')->loadByCode(Mage::getStoreConfig('general/store_information/merchant_country'));
			$merchant_address = Mage::getStoreConfig('general/store_information/address');//.' '.$merchant_country->getName();
			$merchant_address_url =  urlencode($merchant_address);
		    
			$map_url = "http://maps.google.com/maps/api/directions/xml?origin=".$merchant_address_url."&destination=".$customer_address_url."&sensor=false";
			
			//Mage::log($map_url);
			
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
                        
			return $calculated_distance;
			
			
		    }else{
			
			return false;
		    }
	    }
	 
	    return false;
	    
    }
    
    
    public function calculateDistancePrice($distance){
        
        //check distance is in mi / km
        
        //echo $distance.'<br>';
        
        $measure = substr($distance, strlen($distance)-2, strlen($distance));
        
        //echo $measure.'<br>';
        
        //remove thousand seperator if appear
        $seperator_pos = strpos($distance,',');
        if($seperator_pos){
            $distance = str_replace(',','',$distance);
        }
        
        
        //echo $distance.'<br>';
        
        //get the float value only
        $distance = floatval($distance);
        
        //echo $distance.'<br>';
        
        //get price of 1  mile from config
        // assume $25 for i mile
        
        $distance_charges = 0;
        
        
        if($measure == 'mi'){
            
            if(Mage::getStoreConfig(self::HOMESERVICE_DISTANCE_CHARGES_MILE) != ''){
                $distance_charges = Mage::getStoreConfig(self::HOMESERVICE_DISTANCE_CHARGES_MILE);
                $distance_charges = substr($distance_charges,1,strlen($distance_charges)); //remove $ from 
            }
        }else
        if($measure == 'km'){
            
            if(Mage::getStoreConfig(self::HOMESERVICE_DISTANCE_CHARGES_KM) != ''){
                $distance_charges = Mage::getStoreConfig(self::HOMESERVICE_DISTANCE_CHARGES_KM);
                $distance_charges = substr($distance_charges,1,strlen($distance_charges)); //remove $ from 
            }
        }
        
        
        
        $price = $distance * $distance_charges;
        
        
        //echo '<br>Total Price'.$price;
        return $price;
    
    }
    /**
     * will send an email to staff member
     * @param <array> $info all information 
     * required to process an email
     * */
     
    public function email_to_staff($info,$cancel=false)
    {
        
		//const XML_PATH_EMAIL_REPLY_TEMPLATE = 'bookingreservation/reply/email_template';
		/* getting product related info */
		$model_product = Mage::getModel('catalog/product');
		$product_info = $model_product->load($info['product_id'])->getData();
		//$url_product = Mage::getModel('core/url_rewrite')->loadByRequestPath($product_info['url_path']);
		$url_product = Mage::getBaseUrl().$product_info['url_path'];
		$name_product = $product_info['name'];
		/* getting customer related info */
		$model_customer = Mage::getModel('customer/customer');
		$customer_info = $model_customer->load($info['customer_id'])->getData();
		$name_customer = $customer_info['firstname'];
		$email_customer = $customer_info['email'];
		/* getting staff related info */
		$model_staff = Mage::getModel('bookingreservation/staffmembers');
		$staff_info = $model_staff->load($info['staffmember_id']);
		$member_name = $staff_info['member_name'];
		$member_email = $staff_info['staff_email'];
                
		/* get email template */
		$model_email  = Mage::getModel('core/email_template');
		//$mail = Mage::getModel('core/email');
		$email_template = Mage::getStoreConfig('bookingreservation/reply/email_template');//$model_email->loadDefault('bookingreservation_reply_email_template');
		$email_subject = 'Order Placed';
		/* email sender info */
		$email_sender = Mage::getStoreConfig('trans_email/ident_sales/name'); //sender name
		$sender_email_add = Mage::getStoreConfig('trans_email/ident_sales/email');
		/* owner email info */
		$owner_name = Mage::getStoreConfig('trans_email/ident_general/name');
		$owner_email = Mage::getStoreConfig('trans_email/ident_general/email');
		
		$store_id = Mage::app()->getStore()->getId();
		$sender = array('name'=>$email_sender,'email'=>$sender_email_add);
		$var_object = new Varien_Object();
		//Mage::log($email_template);
		$vars = array(
			'first_name'=>$name_customer,
			'product_name'=>$name_product,
			'product_url'=>$url_product,
			'staff_member'=>$member_name,
			'from_time'=>$info['reserve_from_time'],
			'to_time'=>$info['reserve_to_time'],
			'additional_note'=>'',
		);
		//$var_object->setData($vars);
		//$mail->setTemplate(self::XML_PATH_EMAIL_TEMPLATE);
		//$mail->setTemplateVar($vars);
		$html = '
		<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
		<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
		<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
			<tr>
                <td valign="top"><a href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'">
                <img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/default/default/images/logo_email.gif"  style="margin-bottom:10px;" border="0"/></a></td>
            </tr>	
			<tr>
				<td><b>First Name</b></td>
				<td>'.$name_customer.'</td>
			</tr>
			<tr>
				<td><b>Product Name</b></td>
				<td>'.$name_product.'</td>
			</tr>
			<tr>
				<td><b>Product URL</b></td>
				<td>'.$url_product.'</td>
			</tr>
			<tr>
				<td><b>Staff Member</b></td>
				<td>'.$member_name.'</td>
			</tr>
			<tr>
				<td><b>Reserved From</b></td>
				<td>'.$info['reserve_from_time'].'</td>
			</tr>
			<tr>
				<td><b>Reserved To</b></td>
				<td>'.$info['reserve_to_time'].'</td>
			</tr>
			<tr>
				<td><b>Reserved Day</b></td>
				<td>'.$info['reserve_day'].'</td>
			</tr>
			<tr>
				<td><b>Status</b></td>
				<td>'.$info['status'].'</td>
			</tr>
			<tr>
				<td><b>Order Date/Time</b></td>
				<td>'.date("M jS, Y - h:i a", Mage::getModel('core/date')->timestamp(time())).'</td>
			</tr>
		</table>
		</div>
		</body>';
		
               
		$mail = new Zend_Mail();
		$mail->setBodyHtml($html)
		->setFrom($sender_email_add,$email_sender)
		->addTo($member_email,$member_name);
		
		//$mail->setToName($member_name);
		//$mail->setToEmail($member_email);
		
		if ($cancel)
		{
			$mail->addCc($owner_email,$owner_name);
			$mail->addBcc($email_customer,$name_customer);
		}
		
		//$mail->setFromName($email_sender);
		//$mail->setFromEmail($sender_email_add);
		//$mail->setBody($html);
		$mail->setSubject(Mage::helper('bookingreservation')->get_staff_email_subject());
		//$mail->setType('html');// YOu can use Html or text as Mail format

		

		try
		{
			/*$model_email->setDesignConfig(array('area' => 'frontend'))
				->sendTransactional(
				Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
				Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
				'elegant.rao@gmail.com',
				null,
				array('data' => $var_object)
			);*/
			$mail->send();
			//Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
			//$model_email->getProcessedTemplate($vars);
			//$var_object->setData($vars);
			/* sending an email */
			/*$model_email->setDesignConfig(array('area' => 'frontend'))
			->setReplyTo($member_email)
			->sendTransactional($email_template, $sender, null, array('data'=>$var_object));*/
			
			//$model_email->send($member_email,'rao', $vars);
			
			/*if (!$model_email->getSentSuccess())
			{
				Mage::log($sender_email_add.' '.$url_product.' '.$member_name);
				Mage::logException(new Exception('This email cannot be sent.'));
				return;
			}*/
		}
		catch (Exception $exception)
		{
			//Mage::getSingleton('core/session')->addError('Your request not sent');
			//Mage::logException($exception->getMessage());
		}
	}
    
    
    
    
    
    // functions to refund the booking-amout on cancel    
    
    public function refundBookingProduct($order_id,$product_id,$cancel_type){
        
        //$order_id = 49;
        //$product_id = 1;
        
        
        $order = Mage::getModel('sales/order')->load($order_id);
        
        
        $order_item_model = Mage::getModel('sales/order_item');        
        $order_items = $order_item_model->getCollection()
                                    ->setOrderFilter($order_id)
                                    ->addAttributeToFilter('product_id', array('eq'=> $product_id));
        
        // if invoice of this order is created, then move to create creatememo and refund        
        $invoice = Mage::getModel("sales/order_invoice")->getCollection()
                                    ->setOrderFilter($order_id);
        
        if($invoice->getData()){
            
            $invoice_data = $invoice->getData();
            $invoice_id = $invoice_data[0]['entity_id'];
            $invoice_increment_id = $invoice_data[0]['increment_id'];
            
            if($invoice_increment_id){
                
             
                $order_invoice = Mage::getModel("sales/order_invoice")->loadByIncrementId($invoice_increment_id);
                $invoice_items = $order_invoice->getAllItems();
                
                foreach($invoice_items as $item_data){
                    
                    // this is the item, who need refund
                    if($item_data->getProductId() == $product_id){
                        
                        $order_item_id = $item_data->getOrderItemId();
                        $item_qty = (int) $item_data->getQty();                       
                        
                    }
                    
                }
                
                // data to create creditmemo                
                $crmemo_info = array("order_increment_id" => $order->getIncrementId(), "invoice_id" => $invoice_id);                
                
                $adjust_cancel_charges = (int) $this->getBookingCancelCharges();
                
                if($cancel_type == 'charge_cancel' && ($adjust_cancel_charges != '' && $adjust_cancel_charges != 0)){
                    
                    $crmemo_data = array(
                        "items" => array($order_item_id => array('qty'=>$item_qty)),
                        "do_refund" => "1",
                        "comment_text" => "",
                        "shipping_amount" => "0",
                        "adjustment_positive" => "0",
                        "adjustment_negative" => $adjust_cancel_charges,
                    );
                    
                }else{
                
                    $crmemo_data = array(
                        "items" => array($order_item_id => array('qty'=>$item_qty)),
                        "do_refund" => "1",
                        "comment_text" => "",
                        "shipping_amount" => "0",
                        "adjustment_positive" => "0",
                        "adjustment_negative" => "0",
                    );
                }
                
                
                
                    try {
                            $creditmemo = $this->_initCreditmemo($crmemo_data, $crmemo_info);
                            if ($creditmemo) {
                                if (($creditmemo->getGrandTotal() <=0) && (!$creditmemo->getAllowZeroGrandTotal())) {
                                    Mage::throwException(
                                        $this->__('Credit memo\'s total must be positive.')
                                    );
                                }
                    
                                $comment = '';
                                if (!empty($crmemo_data['comment_text'])) {
                                    $creditmemo->addComment(
                                        $crmemo_data['comment_text'],
                                        isset($crmemo_data['comment_customer_notify']),
                                        isset($crmemo_data['is_visible_on_front'])
                                    );
                                    if (isset($crmemo_data['comment_customer_notify'])) {
                                        $comment = $crmemo_data['comment_text'];
                                    }
                                }
                    
                                if (isset($crmemo_data['do_refund'])) {
                                    $creditmemo->setRefundRequested(true);
                                }
                                if (isset($crmemo_data['do_offline'])) {
                                    $creditmemo->setOfflineRequested((bool)(int)$crmemo_data['do_offline']);
                                }
                                
                                $creditmemo->register();
                                if (!empty($crmemo_data['send_email'])) {
                                    $creditmemo->setEmailSent(true);
                                }
                    
                                $creditmemo->getOrder()->setCustomerNoteNotify(!empty($crmemo_data['send_email']));
                                $this->_saveCreditmemo($creditmemo);
                                $creditmemo->sendEmail(!empty($crmemo_data['send_email']), $comment);
                                Mage::getSingleton('core/session')->addNotice(Mage::helper('customer')->__('Booking has been canceled, Payments Refunded'));
                                //Mage::getSingleton('adminhtml/session')->getCommentText(true);
                                return;
                            } else {
                                //$this->_forward('noRoute');
                                //return;
                            }
                        } catch (Mage_Core_Exception $e) {
                            Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account'));   
                            Mage::app()->getResponse()->sendResponse();
                            Mage::getSingleton('core/session')->addError($e->getMessage());
                            exit;
                            
                        } catch (Exception $e) {
                            Mage::logException($e);
                            Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account'));   
                            Mage::app()->getResponse()->sendResponse();
                            Mage::getSingleton('core/session')->addError($this->__('Cannot save the credit memo.'));
                            exit;
                        }                
                
                //echo "<pre>"; print_r($crmemo_info);
            }
        
        
        }else{
        
        
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account'));   
                Mage::app()->getResponse()->sendResponse();
                        
                Mage::getSingleton('core/session')->addNotice(Mage::helper('customer')->__('Sorry, Payments are pending'));                        
                exit;
        }
    }
    
    
    
    
    protected function _initCreditmemo($data, $info, $update = false)
    {
        $creditmemo = false;
        $invoice=false;
        $creditmemoId = null;//$this->getRequest()->getParam('creditmemo_id');
        $orderId = $info['order_increment_id'];//$this->getRequest()->getParam('order_id');
        $invoiceId = $data['invoice_id'];
        
        if ($creditmemoId) {
            $creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoId);
        } elseif ($orderId) {
            $order  = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            if ($invoiceId) {
                $invoice = Mage::getModel('sales/order_invoice')
                    ->load($invoiceId)
                    ->setOrder($order);
                echo '<br>loaded_invoice_number: '.$invoice->getId();
            }
    
             if (!$order->canCreditmemo()) {
                echo '<br>cannot create credit memo'; 
                if(!$order->isPaymentReview())
                {
                    echo '<br>cannot credit memo Payment is in review'; 
                }
                if(!$order->canUnhold())
                {
                    echo '<br>cannot credit memo Order is on hold'; 
                }
                if(abs($order->getTotalPaid()-$order->getTotalRefunded())<.0001)
                {
                    echo '<br>cannot credit memo Amount Paid is equal or less than amount refunded'; 
                }
                if($order->getActionFlag('edit') === false)
                {
                    echo '<br>cannot credit memo Action Flag of Edit not set'; 
                }
                if ($order->hasForcedCanCreditmemo()) {
                    echo '<br>cannot credit memo Can Credit Memo has been forced set'; 
                }
                return false;
            }
    
            $savedData = array();
            if (isset($data['items'])) {
                $savedData = $data['items'];
            } else {
                $savedData = array();
            }
    
            $qtys = array();
            $backToStock = array();
            foreach ($savedData as $orderItemId =>$itemData) {
                if (isset($itemData['qty'])) {
                    $qtys[$orderItemId] = $itemData['qty'];
                }
                if (isset($itemData['back_to_stock'])) {
                    $backToStock[$orderItemId] = true;
                }
            }
            $data['qtys'] = $qtys;
    
            $service = Mage::getModel('sales/service_order', $order);
            if ($invoice) {
                $creditmemo = $service->prepareInvoiceCreditmemo($invoice, $data);
            } else {
                $creditmemo = $service->prepareCreditmemo($data);
            }
    
            /**
             * Process back to stock flags
             */
            foreach ($creditmemo->getAllItems() as $creditmemoItem) {
                $orderItem = $creditmemoItem->getOrderItem();
                $parentId = $orderItem->getParentItemId();
                if (isset($backToStock[$orderItem->getId()])) {
                    $creditmemoItem->setBackToStock(true);
                } elseif ($orderItem->getParentItem() && isset($backToStock[$parentId]) && $backToStock[$parentId]) {
                    $creditmemoItem->setBackToStock(true);
                } elseif (empty($savedData)) {
                    $creditmemoItem->setBackToStock(Mage::helper('cataloginventory')->isAutoReturnEnabled());
                } else {
                    $creditmemoItem->setBackToStock(false);
                }
            }
        }
    
        return $creditmemo;
    }

   
    protected function _saveCreditmemo($creditmemo)
    {
        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($creditmemo)
            ->addObject($creditmemo->getOrder());
        if ($creditmemo->getInvoice()) {
            $transactionSave->addObject($creditmemo->getInvoice());
        }
        $transactionSave->save();
    
        return $this;
    }
 
    
    
    
    
    
    
    
    

}
