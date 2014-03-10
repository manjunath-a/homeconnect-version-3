<?php
/**
 * TemplateMela
 * @copyright  Copyright (c) 2010 TemplateMela. (http://www.templatemela.com)
 * @license    http://www.templatemela.com/license/
 */

class Megnor_Framework_Helper_Data extends Mage_Core_Helper_Abstract { 
	
	public function isNewProduct($_product) {			
		$now = date("Y-m-d");
		$newsFrom= substr($_product->getData('news_from_date'),0,10);
		$newsTo=  substr($_product->getData('news_to_date'),0,10);		
		if(($now >= $newsFrom) && ($now <= $newsTo)){
			return true;
		}else{
			return false;
		}
    }

	
	public function isSpecialProduct($_product) {			
		$now = date("Y-m-d");
		$specialFrom= substr($_product->getData('special_from_date'),0,10);
		$specialTo=  substr($_product->getData('special_to_date'),0,10);
		if((($now >= $specialFrom) && ($now <= $specialTo) || ($_product->special_price !== null) || ($_product->_rule_price !== null))){
			return true;
		}else{
			return false;
		}
    }
	
	
}
