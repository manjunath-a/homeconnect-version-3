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
class VDesign_Bookme_Helper_Availible extends Mage_Core_Helper_Abstract{
	
	
	public function isAvailible($product, $date, $qty){
		if(is_numeric($product))
			$product = Mage::getModel('catalog/product')->load($product);
		else
			$product = Mage::getModel('catalog/product')->load($product->getId());
		
		if($this->isInExcluded($product, $date))
			return false;
		
		$resource = Mage::getSingleton('core/resource');
		$booke_item_table = $resource->getTableName('bookme/book_item');
		
		$sql = "SELECT * FROM $booke_item_table".
				" WHERE `product_id` = ".$product->getId(). 
				" AND `booked_from` <= '$date'".
				" AND `booked_to` >= '$date'";
		
		$bookCollection = Mage::getSingleton('core/resource')
			->getConnection('core_read')
			->fetchAll($sql);
		
		$bookedQty = 0;
		foreach ($bookCollection as $item){
			$bookedQty += $item['qty'];
		}
		
		$qty = $bookedQty + $qty;
		$availible = $product->getData('bookable_qty');
		
		if ($qty <= $availible){
			return true;
		}
		else{
			return false;
		}
		
		return true;
	}
	
	public function getAvailibleQty($product, $date){
		
		$resource = Mage::getSingleton('core/resource');
		$booke_item_table = $resource->getTableName('bookme/book_item');
		
		$sql = "SELECT * FROM $booke_item_table".
				" WHERE `product_id` = ".$product->getId().
				" AND `booked_from` <= '$date'".
				" AND `booked_to` >= '$date'";
		
		$bookCollection = Mage::getSingleton('core/resource')
				->getConnection('core_read')
				->fetchAll($sql);
	
		$bookedQty = 0;
		foreach ($bookCollection as $item){
			$bookedQty += $item['qty'];
		}
		
		$availible = $product->getData('bookable_qty');

		return $availible - $bookedQty;
	}
	
	public function isInExcluded($product, $date){
		$exludeDays = $product->getData('exclude_day');
		foreach ($exludeDays as $exday){
			if($exday['period_type'] == 1){
				$exfrom = $exday['from_date'];
				$exTo = $exday['to_date'];
				if(strtotime($exfrom) <= strtotime($date) && strtotime($exTo) >= strtotime($date))
					return true;
				else continue;
			}
			
			if($exday['period_type'] == 2){
				$exday = strtotime($exday['value']);
				$exday = date('Y-m-d', $exday);
				if(strtotime($date) == strtotime($exday))
					return true;
				else continue;
			}
			
			if($exday['period_type'] == 3){
				$dw = date( "w", strtotime($date));
				if($exday['value'] == $dw)
					return true;
				else continue;
			}
			
			if($exday['period_type'] == 4){
				$day = explode("-", date('Y-m-d', strtotime($date)));
				if($day[2] == $exday['value'])
					return true;
				else continue;
			}
		}
		return false;
	}
	
	public function getNameOfDay($day){
		if($day == 1)
			return 'monday';
		if($day == 2)
			return 'tuesday';
		if($day == 3)
			return 'wednesday';
		if($day == 4)
			return 'thuersday';
		if($day == 5)
			return 'friday';
		if($day == 6)
			return 'saturday';
		if($day == 7)
			return 'sunday';
	}
	
	public function getExcludeDays($product_id, $month, $year){
		
		$product = Mage::getModel('catalog/product')->load($product_id);
		
		if(!$product->getId())
			return '';
		
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$out = '';
		foreach ($product->getData('exclude_day') as $day){
			if($day['period_type'] == 1)
				$out .= $this->addExFromRange($day);
			if($day['period_type'] == 2)
				$out .= date('Y/m/d', strtotime($day['value'])).',';
			if($day['period_type'] == 3)
				$out .= $this->addExFromWeek($month, $day['value'], $year);
			if($day['period_type'] == 4)
				$out .= $this->addExFromMonth($month, $day['value'], $year).',';
		}
	
		if($product->getAttributeText('billable_period') != 'Session')
		{
			foreach ($this->getFullyBookedDays($product, $month, $year) as $key => $value){
				if($value >= $product->getData('bookable_qty'))
					$out .= date('Y/m/d', $key).',';
			}	
		}
		
		
		$out = ($out != '')? substr($out, 0, strlen($out) - 1) : 'null';
		
		return $out;
	}
	
	public function getFullyBookedDays($product, $month, $year){
		
		$id = $product->getId();
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$month ++;
		$from_date = $year.'-'.$month.'-01 00:00:00';
		$month ++;
		$to_date = $year.'-'.$month.'-01 00:00:00';
		
		$resource = Mage::getSingleton('core/resource');
		$booke_item_table = $resource->getTableName('bookme/book_item');
		
		$sql = "SELECT `booked_from`, `booked_to`, `qty` 
				FROM $booke_item_table WHERE (
					(`booked_from` >= '$from_date' AND `booked_from` <= '$to_date')
						 OR 
					(`booked_to` >= '$from_date' AND `booked_to` <= '$to_date')
				) AND `product_id` = $id";
		
		$bookCollection = Mage::getSingleton('core/resource')
			->getConnection('core_read')
			->fetchAll($sql);
		
		$bookdata = array();
		foreach ($bookCollection as $item){
			$from = strtotime($item['booked_from']);
			$to = strtotime($item['booked_to']);
			
			for($i = $from; $i <= $to; $i += 24 * 60 *60){
				$bookdata[$i] = (isset($bookdata[$i]))? $bookdata[$i] + $item['qty'] : $item['qty'];
			}
			
		}
		return $bookdata;
	}
	
	public function addExFromRange($day){
		$timeFrom = strtotime($day['from_date']) * 1000;
		$timeTo   = strtotime($day['to_date']) * 1000;
		$out = '';
		for($i = $timeFrom; $i <= $timeTo; $i += 24 * 60 * 60 * 1000)
			$out .= date('Y/m/d', $i / 1000).',';
		return $out;
	}
	
	public function addExFromMonth($month, $day, $year){
		if($day == 32){
			$time = strtotime($year.'-'.($month + 1).'-01');
			$date = date('Y-m-t', $time);
			$time = strtotime($date) * 1000;
		}else
			$time = strtotime($year.'-'.($month + 1).'-'.$day)*1000;
		return date('Y/m/d', $time / 1000);
	}
	
public function addExFromWeek($month, $day, $year){
		
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$out = ''; $day = ($day == 7)? 0 : $day;
		$first_d_in_m = strtotime($year.'-'.$month.'-01 00:00:00');
		
		$day_in_week = (int) date('w', $first_d_in_m);
		
		$first_d_in_m = $first_d_in_m * 1000;
		for($i = ($day_in_week > $day)? ($day_in_week - 7) : $day_in_week; $i < $day; $i++){
			$first_d_in_m += 24 * 60 * 60 * 1000;
			if($i == 7) $i = 0;
		}
		
		if((int) date('w', $first_d_in_m / 1000) < $day)
			$first_d_in_m += 3 * 60 * 60 * 1000;
		
		if((int) date('w', $first_d_in_m / 1000) > $day)
			$first_d_in_m -= 3 * 60 * 60 * 1000;
		
		$milis = $first_d_in_m;
		for($i = 0; $i <= 5; $i++)
		{
			if(date('m', $milis / 1000) == $month)
				$out .= date('Y/m/d', $milis / 1000).',';
			$milis += 7*24.5*60*60*1000;
		}
		return $out;
	}
	
	function getFirstWeekDay($day, $month, $year) {
	
		$num = date('w',mktime(0, 0, 0, $month, (int)$day, $year));
		if($num>1)
			return date('Y-M-d H:i:s',mktime(0,0,0,$month,(int)$day,$year)+(86400*(8-$num)));
			else
			return date('Y-M-d H:i:s',mktime(0,0,0,$month,(int)$day,$year));
	}
	
	public function getSessions($product_id, $date){
		
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		$output = '';
		$product = Mage::getModel('catalog/product')->load($product_id);
		
		if(!$product->getId())
			return '';
		
		if($product->getAttributeText('billable_period') == 'Session'){
			$sessions = $product->getData('custom_session');
			
			
			$dw = date("N", ($date / 1000));
			
			/*
			 * at first look up by for 'every day' session
			*/
			foreach ($sessions as $session){
				if($session['session_day'] == 10){
					$output = '';
					foreach ($session['sessions'] as $time){
						$session_timestamp = (($time['hour'] * 60 * 60) + ($time['minute'] * 60));
						$output .= $session_timestamp.'#'.$this->isSessionAvailible($product, $date + ($session_timestamp * 1000)).',';
					}
				}
			}
			
			/*
			 * next search for weekend or business day
			 */
			if($dw > 0 && $dw < 6){
				foreach ($sessions as $session){
					if($session['session_day'] == 8){
						$output = '';
						foreach ($session['sessions'] as $time){
							$session_timestamp = (($time['hour'] * 60 * 60) + ($time['minute'] * 60));
							$output .= $session_timestamp.'#'.$this->isSessionAvailible($product, $date + ($session_timestamp * 1000)).',';
						}
					}
				}	
			}else{
				foreach ($sessions as $session){
					if($session['session_day'] == 9){
						$output = '';
						foreach ($session['sessions'] as $time){
							$session_timestamp = (($time['hour'] * 60 * 60) + ($time['minute'] * 60));
							$output .= $session_timestamp.'#'.$this->isSessionAvailible($product, $date + ($session_timestamp * 1000)).',';
						}
					}
				}
			}
			
			
			/*
			 * next search for specific day in week
			 */
			foreach ($sessions as $session){
				if($session['session_day'] == $dw){
					$output = '';
					foreach ($session['sessions'] as $time){
						$session_timestamp = (($time['hour'] * 60 * 60) + ($time['minute'] * 60));
						$output .= $session_timestamp.'#'.$this->isSessionAvailible($product, $date + ($session_timestamp * 1000)).',';
					}
				}
			}
			
			/*
			 * at last check if there is secific day setted
			*/
			foreach ($sessions as $session){
				if($session['session_day'] == 11 && isset($session['spec_day'])){
					if($session['spec_day'] == date('Y-m-d H:i:s', $date / 1000)){
						$output = '';
						foreach ($session['sessions'] as $time){
							$session_timestamp = (($time['hour'] * 60 * 60) + ($time['minute'] * 60));
							$output .= $session_timestamp.'#'.$this->isSessionAvailible($product, $date + ($session_timestamp * 1000)).',';
						}
					}
				}
			}
			
			return substr($output, 0, strlen($output) - 1);
		}
	
		return '';
	}
	
	public function isSessionAvailible($product, $time){
		
		$date = date('Y-m-d H:i:s', $time / 1000);
		
		$resource = Mage::getSingleton('core/resource');
		$booke_item_table = $resource->getTableName('bookme/book_item');
		
		$sql = "SELECT * FROM $booke_item_table".
				" WHERE `product_id` = ".$product->getId().
				" AND ( `booked_from` = '$date'".
				" OR `booked_to` = '$date' )";
		
		$bookCollection = Mage::getSingleton('core/resource')
				->getConnection('core_read')
						->fetchAll($sql);

		$bookedQty = 0;
		foreach ($bookCollection as $item){
			$bookedQty += $item['qty'];
		}
		
		return ($bookedQty < $product->getData('bookable_qty'))? '1' : '0';
	}
}