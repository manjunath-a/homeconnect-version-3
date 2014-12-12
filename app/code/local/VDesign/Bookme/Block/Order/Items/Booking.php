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

class VDesign_Bookme_Block_Order_Items_Booking
extends Mage_Sales_Block_Order_Item_Renderer_Default
{
public function getItemOptions()
	{
		$result = array();
		if ($options = $this->getItem()->getOrderItem()->getProductOptions()) {
			if (isset($options['options'])) {
				$result = array_merge($result, $options['options']);
			}
			if (isset($options['additional_options'])) {
				$result = array_merge($result, $options['additional_options']);
			}
			if (isset($options['attributes_info'])) {
				$result = array_merge($result, $options['attributes_info']);
			}
		}
	
	    for ($i = 0; $i < count($result); $i++)
        {
            if($result[$i]['option_type'] == 'multidate_type'){
        		$value = $this->getBookValue($result[$i]['value']);
        		$result[$i]['value'] = $value;
        		$result[$i]['print_value'] = $value;
        		$result[$i]['option_value'] = $value;
        	}
        }
        return $result;
    }
    
    
    public function getBookValue($data){
    	date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
    	
    	$values = explode(",", $data);
    	$from = date("d-m-Y", $values[0]/1000);
    	
    	if(count($values) == 1 || strtotime($from) < $values[0] / 1000){
    		$out = '';
    		for($i = 0; $i < count($values) - 1; $i++)
    			$out .= Mage::helper('core')->formatDate(date("d-m-Y H:i:s", $values[$i]/1000), 'medium', true).'<br />';
    		
    		return $out;
    	}else{
    		$to = date("d-m-Y", $values[count($values)-2]/1000);
    		return  Mage::helper('core')->formatDate($from, 'medium', false).' - '.Mage::helper('core')->formatDate($to, 'medium', false);
    	}
    }
}