<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to vdesign.support@outlook.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @copyright  Copyright (c) 2014 VDesign
 * @license    End User License Agreement (EULA)
 */
class VDesign_Bookme_Model_Product_Attribute_Backend_Customsession
extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract{
	
	const TYPE_PERIOD = '1';
	const TYPE_DATE = '2';
	const TYPE_MONTH = '3';
	const TYPE_WEEK = '4';
	
	/**
	 * Retrieve resource instance
	 *
	 */
	protected function _getResource()
	{
		return Mage::getResourceSingleton('bookme/product_attribute_backend_customsession');
	}
	
	
	public function validate($object){
	}

	public function afterSave($object)
	{
		
		$customSessions = $object->getData($this->getAttribute()->getName());
				
		foreach ($customSessions as $session){
			$session_object = Mage::getModel('bookme/book_session');
			
			if(isset($session['session_id'])){
				$session_object = $session_object->load($session['session_id']);
				if(!$session_object->getId()){
					unset($session['session_id']);
					$session_object = Mage::getModel('bookme/book_session');
				}else{
					if($session['deleted'] == 1){
						$session_object->delete();
						continue;
					}
				}
			}
			
			if(isset($session['deleted']))
				if($session['deleted'] == 1){
					continue;
				}
			
			$session_object->setProduct($object);
			$session_object->setSessionDay((isset($session['session_day']))? $session['session_day'] : $session['day']);
			
			if(isset($session['day']))
			{
				if($session['day'] == 11)
				{
					$session_object->setSpecDay($session['specific_date']);
				}else{
					$session_object->setSpecDay(NULL);
				}	
			}else
			{
				if($session['session_day'] == 11)
				{
					$session_object->setSpecDay($session['specific_date']);
				}else{
					$session_object->setSpecDay(NULL);
				}
			}
			
			
			$session_object->save();
			//from classic saving, it comes as session_times
			if(isset($session['session_times'])){
				foreach ($session['session_times'] as $time){
					$time_object = Mage::getModel('bookme/book_session_time');
					if(isset($time['session_time_id'])){
						$time_object = $time_object->load($time['session_time_id']);
						
						if(!$time_object->getId()){
							unset($time['session_time_id']);
							$time_object = Mage::getModel('bookme/book_session_time');
						}else{
							if($time['deleted'] == 1){
								$time_object->delete();
								continue;
							}
						}
					}
					
					if($time['deleted'] == 1){
						continue;
					}
					
					$time_object->setSession($session_object);
					$time_object->setHour($time['hour']);
					$time_object->setMinute($time['minute']);
					$time_object->save();
				}
			
			}
			
			//from duplicating operation, it comes as sessions
			if(isset($session['sessions'])){
				foreach ($session['sessions'] as $time){
					$time_object = Mage::getModel('bookme/book_session_time');
					if(isset($time['session_time_id'])){
						$time_object = $time_object->load($time['session_time_id']);
			
						if(!$time_object->getId()){
							unset($time['session_time_id']);
							$time_object = Mage::getModel('bookme/book_session_time');
						}else{
							if($time['deleted'] == 1){
								$time_object->delete();
								continue;
							}
						}
					}
						
					if(isset($session['deleted']))
						if($time['deleted'] == 1){
							continue;
						}
						
					$time_object->setSession($session_object);
					$time_object->setHour($time['hour']);
					$time_object->setMinute($time['minute']);
					$time_object->save();
				}
					
			}
		}
		return $this;
	}
	
	public function afterLoad($object){
		
		$data = array();
		$collection = Mage::getModel('bookme/book_session')->getCollection()->addFilter('entity_id', $object->getId());
		
		foreach ($collection as $item){
			$pom = array();
			foreach ($item->getData() as $key => $value){
				if($key == 'session_id'){
					$time_collection = Mage::getModel('bookme/book_session_time')->getCollection()->addFilter('session_id', $value);
					$times = array();
					foreach ($time_collection as $time){					
						foreach ($time->getData() as $skey => $svalue){
							$times[$skey] = $svalue;
						}
						$pom['sessions'][] = $times;
					}
				}
				$pom[$key] = $value;
			}
			$data[] = $pom;
		}
		
		$object->setData($this->getAttribute()->getAttributeCode(), $data);
		$object->setOrigData($this->getAttribute()->getAttributeCode(), $data);
		
		return $this;
	}

}