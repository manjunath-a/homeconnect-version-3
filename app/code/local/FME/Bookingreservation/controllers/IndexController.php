<?php
class FME_Bookingreservation_IndexController extends Mage_Core_Controller_Front_Action
{
    
    public function indexAction()
    {	
		$this->loadLayout();     
		$this->renderLayout();
    }
    
    
    
    public function getBookingInfoAction(){
	
	$booking_collection = Mage::getModel('bookingreservation/bookingreservation')->getCollection()
						->addFieldToFilter( 'status', array('in'=> array('complete','processing')));
				
				
	$selected_day = $this->getRequest()->getParam('selected_day');
	$selected_product = $this->getRequest()->getParam('selected_product');
	$selected_store = $this->getRequest()->getParam('selected_store');
	$selected_member = $this->getRequest()->getParam('selected_member');
	
	
	
	
	if($selected_day){
	    
		    //get orders of current day and plus only
		    $booking_collection->addFieldToFilter( 'reserve_day', array('eq'=> $selected_day));
		    
		    //$booking_collection->addStoreFilter($selected_store);		    
		    //$booking_collection->addFieldToFilter( 'store_id', array('eq'=> $selected_store));
		    
		    // then get the reserve time of member overall
		    if($selected_member != 0){
			
			$booking_collection->addFieldToFilter( 'staffmember_id', array('eq'=> $selected_member));
		    
		    }else{ //if staffmember is not selected then get the reserve time of that product
			
			$booking_collection->addFieldToFilter( 'product_id', array('eq'=> $selected_product));
		    }
		    
		    
		    $booking_data = $booking_collection->getData();
		    
		    $booking_info_array = array();
		    
		    if($booking_data != 0){    
			
			$count = 0;
			
			foreach($booking_data as $booking_row){
			    
				$reserve_day = $booking_row['reserve_day'];
				$from_time = $booking_row['reserve_from_time'];
				$to_time = $booking_row['reserve_to_time'];				
				
				    //$booking_info_array[$count]['reserve_day'] = $reserve_day; 
				    $booking_info_array[$count]['reserve_from_time'] = $from_time;
				    $booking_info_array[$count]['reserve_to_time'] = $to_time;
				
				$count++;
				
			}
			
			
		    }
		    
		    echo json_encode($booking_info_array);
		    
		    exit;
	}
	
    }
    
    
    
    
    public function getMembersAvailableTimeAction(){
	
	
	
	$selected_day = $this->getRequest()->getParam('selected_day');
	$selected_member = $this->getRequest()->getParam('selected_member');
	
	//echo $selected_day;
	
	if($selected_member != 0){
	
	    $selected_member_data = Mage::getModel('bookingreservation/staffmembers')->load($selected_member);
	    
	    $start_time = $selected_member_data->getAllDaysStart();
	    $end_time = $selected_member_data->getAllDaysEnd();
	    
	    if($start_time == '' && $end_time == ''){
		
		    if($selected_day == 'Monday'){
			
			$start_time = $selected_member_data->getMondayStart();
			$end_time = $selected_member_data->getMondayEnd();
			
		    }else
		    if($selected_day == 'Tuesday'){
			
			$start_time = $selected_member_data->getTuesdayStart();
			$end_time = $selected_member_data->getTuesdayEnd();
			
		    }
		    else
		    if($selected_day == 'Wednesday'){
			
			$start_time = $selected_member_data->getWednesdayStart();
			$end_time = $selected_member_data->getWednesdayEnd();
			
		    }
		    else
		    if($selected_day == 'Thursday'){
			
			$start_time = $selected_member_data->getThursdayStart();
			$end_time = $selected_member_data->getThursdayEnd();
			
		    }
		    else
		    if($selected_day == 'Friday'){
			
			$start_time = $selected_member_data->getFridayStart();
			$end_time = $selected_member_data->getFridayEnd();
			
		    }
		    else
		    if($selected_day == 'Saturday'){
			
			$start_time = $selected_member_data->getSaturdayStart();
			$end_time = $selected_member_data->getSaturdayEnd();
			
		    }
		    else
		    if($selected_day == 'Sunday'){
			
			$start_time = $selected_member_data->getSundayStart();
			$end_time = $selected_member_data->getSundayEnd();
			
		    }
	    }
	    
	
	
	    //echo $selected_day;
	    
	    $result_array = array(
		
		'member_start_time' => $start_time,
		'member_end_time' => $end_time	    
	    );
	
	    
	    
	    echo json_encode($result_array);
	    exit;
	    
	}
	
	
    }
    
    
    
    
    public function getAvailableMembersAction(){
	
	
	$selected_product_id 	= $this->getRequest()->getParam('selected_product');
	$selected_day_name	= $this->getRequest()->getParam('selected_day_name');
	
	$resource 	= Mage::getSingleton('core/resource');
	$connection 	= $resource->getConnection('core_read');	
	$b_s_p_table_name 	= $resource->getTableName('booking_staff_products');
	$staff_table_name 	= $resource->getTableName('staffmembers');
	
	
	$query = $connection->select()->from(array('b_s_p'=>$b_s_p_table_name))
					->join(array('staff'=>$staff_table_name),'staff.staffmembers_id = b_s_p.staffmembers_id', array('*'))
					->where('b_s_p.product_id =?', $selected_product_id);
					
					
	$query_result = $connection->fetchAll($query);
	
	if($query_result){
	    
	    $html = '';
	    $member_on = false;
	    $selected_day_name = strtolower($selected_day_name);
	    $selected_day_start = $selected_day_name.'_start'; 
	    $selected_day_end 	= $selected_day_name.'_end';
	    
	    
	    foreach($query_result as $data){
		
		$member_on = false;
		
		if($data['all_days_start'] != '' && $data['all_days_end'] != ''){
		    
		    if($data['all_days_start'] != 'closed' && $data['all_days_end'] != 'closed'){
		    
			$member_on = true;
		    }
		    
		}
		else{		    
		    
		    if($data[$selected_day_start] != '' && $data[$selected_day_end] != ''){
			
			if($data[$selected_day_start] != 'closed' && $data[$selected_day_end] != 'closed'){
			    
			    $member_on = true;
			    
			}
		    }
		}
		
		if($member_on){
		    
		    $html.= '<div class="staff-member-info">';
		    $html.= '<img src="'.$data['member_pic'].'" title="'.$data['member_name'].'" alt="'.$data['member_name'].'" width="80px;" height="80px;"><br>';
		    $html.= '<strong>'.$data['member_name'].'</strong><br>';
		    $html.= '<input type="radio" name="booking_staff_members" id="booking_staff_members" value="'.$data['staffmembers_id'].'" />';                    
		    $html.= '</div>';		    
		}
		
	    }	    
	    
		
		$html.= '<div class="staff-member-info">';
                $html.= '<img src="'.Mage::getDesign()->getSkinUrl('images/bookingreservation/default_image.jpg').'" title="'.$this->__('Any One').'" alt="'.$this->__('Any One').'" width="80px;"><br>';
                $html.= '<strong>'.$this->__('Any One').'</strong><br>';
                $html.= '<input type="radio" name="booking_staff_members" value="0" checked="checked" />';
		$html.= '</div>'; 
	}
	
	
	
	
	
	echo $html;
	
	exit;
	
    }
    
    
    
    
    
    
    
    public function changeBookingStatusAction(){
	
	$params = $this->getRequest()->getParams();
	
	//echo "<pre>"; print_r($params); exit;
	
	
	$order_id = $params['order_id'];
	$booking_id = $params['booking_id'];
	$booking_cancel_type = $params['booking_cancel'];
	
	
	if($order_id && $booking_id){
	    
	    $booking_model = Mage::getModel('bookingreservation/bookingreservation');
	    $booking = $booking_model->load($booking_id);
	    $booking_status = $booking->getStatus();
	    $booking_product_id = $booking->getProductId();
	    
	    /*
	    // change order status to cancel	    
	    $order_model = Mage::getModel('sales/order');	    
	    $order = $order_model->load($order_id);	    
	    
	    //now get items of that order, and delete them, then update the order	    
	    
	    if($order->getTotalItemCount() > 1){
		
		$base_grand_total = $order->getBaseGrandTotal();
		$base_subtotal = $order->getBaseSubtotal();
		$grand_total = $order->getGrandTotal();
		$subtotal = $order->getSubtotal();
		
		$base_subtotal_incl_tax = $order->getBaseSubtotalInclTax();
		$subtotal_incl_tax = $order->getSubtotalInclTax();
		$total_item_count = $order->getTotalItemCount();
		
		$items = $order->getAllItems(); 
		foreach($items as $item){		
			
			if($item->getParentItemId() == '' || $item->getParentItemId() == null){
			    
			    $product_id = $item->getProductId();
			    if($product_id == $booking_product_id){			    
				
				//remove item price from total price of order
				$item_price = $item->getPrice();
				$item->delete();
				
				$order->setBaseGrandTotal($base_grand_total-$item_price);
				$order->setBaseSubtotal($base_subtotal-$item_price);
			        $order->setGrandTotal($grand_total-$item_price);
				$order->setSubtotal($subtotal-$item_price);
				
				$order->setBaseSubtotalInclTax($base_subtotal_incl_tax-$item_price);
				$order->setSubtotalInclTax($subtotal_incl_tax-$item_price);
				$order->setTotalItemCount($total_item_count-1);
				$order->save();	
			    }
			    
			}
		    
		}
		
		//cancel that booking
		//$booking->setStatus('canceled');
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$booking_table = $connection->getTableName('bookingreservation');	
		$connection->beginTransaction();
		
		$fields = array();
		$fields['status'] = 'canceled';
		
		$where = $connection->quoteInto('bookingreservation_id =?', $booking_id);		
		$connection->update($booking_table, $fields, $where);		
		$connection->commit();		
		
	    
	    }else{ // order has only one item so change its status	    
	      
		    if($order && $booking_status!= 'canceled'){
			
			// this will also change the booking status to cancel by Event in observer
			$order->setState('canceled', true);			
			$order->save();	
		    }
	    }
	    */
	    
	    
	    // Refund on cancelation 
	    
	    if($booking_cancel_type == 'free_cancel' || $booking_cancel_type == 'charge_cancel'){
		
		Mage::helper('bookingreservation')->refundBookingProduct($order_id,$booking_product_id,$booking_cancel_type);
		
	    }
	    
	    
	    
	    
	    $_info_to_email = array(
			'customer_id'=>$booking->getCustomerId(),
			'staffmember_id'=>$booking->getStaffmemberId(),
			'product_id' =>$booking->getProductId(),
			'reserve_from_time'=>$booking->getReserveFromTime(),
			'reserve_to_time'=>$booking->getReserveToTime(),
			'status'=>'canceled',
			'reserve_day'=>$booking->getReserveDay()
	    );
	    
	    Mage::helper('bookingreservation')->email_to_staff($_info_to_email,true);
	}
	
	
	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
	
    }
    
    
    
    
    
    
    
    
    
    public function getBookingAddressDistanceAction(){
	
	    $params = $this->getRequest()->getParams();	
	    
	    
	    if($params['customer_booking_address']){
		
		    $customer_address = $params['customer_booking_address'];
		    $customer_address_url =  urlencode($customer_address);
		    
		    
		    if(Mage::getStoreConfig('general/store_information/merchant_country')){
			
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
			echo $calculated_distance;
			
			
		    }else{
			
			echo "Merchant Store Address Not Found";
		    }
	    }
	 
	    exit;
	    
    }
    
    
    
    
    public function getCurrentDayStoreOpenAction(){
	
	
	$params = $this->getRequest()->getParams();    
	    
	if($params['product_id']){
	    
	    $day_name = $params['dayname'];
	    
	    $product = Mage::getModel('catalog/product')->load($params['product_id']);
	    
	    $func_start_hrs = 'get'.$day_name.'BusinessHrsStarts';
	    $func_end_hrs = 'get'.$day_name.'BusinessHrsEnds';
	    
	    if($product->$func_start_hrs() == 'closed' ||  $product->$func_end_hrs()  == 'closed')
	    {
		echo "closed";
	    }else{
		
		echo "open";
	    }	        
	    
	    exit;
	}
	
	
    }
    
    
    
    
    
    
    public function refundBookingItemAction(){
	
	$ap_date = '08:00 am';
	 
	$n_date = date('h:i a',strtotime('-120 minutes',strtotime($ap_date)));
	
	echo $n_date;
	exit;	  
	//Mage::helper('bookingreservation')->refundBookingProduct();
	
	
    }
    
}
