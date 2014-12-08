<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Model
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Catalog_Product_Options_Simple extends Simi_Connector_Model_Abstract {

    public $_product;

    public function getOptions($product) {
        $this->_product = $product;
        $infomation = array();
        $this->addOption($infomation);
        return $infomation;
    }

    public function getPrice($price, $includingTax = null) {
        if (!is_null($includingTax)) {
            $price = Mage::helper('tax')->getPrice($this->_product, $price, true);
        } else {
            $price = Mage::helper('tax')->getPrice($this->_product, $price);
        }
        return $price;
    }

    public function setOptionPriceTax(&$info, $price) {
        $taxHelper = Mage::helper('tax');
        $_priceInclTax = Mage::helper('core')->currency($this->getPrice($price, true), false, FALSE);
        $_priceExclTax = Mage::helper('core')->currency($this->getPrice($price), false, FALSE);
        if ($taxHelper->displayPriceIncludingTax()) {
            $info['option_price'] = $_priceInclTax;
        } elseif ($taxHelper->displayPriceExcludingTax()) {
            $info['option_price'] = $_priceExclTax;
        } elseif ($taxHelper->displayBothPrices()) {
            $info['option_price'] = $_priceExclTax;
            $info['option_price_incl_tax'] = $_priceInclTax;
        }
    }
	
	public function addOption(&$infomation, $product=null){
		if($product == null){
			$product = $this->_product;
		}else{
			$this->_product = $product;
		}
		foreach ($product->getOptions() as $_option) {
            $type = '';
            if ($_option->getType() == 'multiple' || $_option->getType() == 'checkbox') {
                $type = 'multi';
            } elseif ($_option->getType() == 'drop_down' || $_option->getType() == 'radio') {
                $type = 'single';
            }
            /* @var $option Mage_Catalog_Model_Product_Option */
//            $priceValue = 0;
            if ($_option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
//                $_tmpPriceValues = array();
                foreach ($_option->getValues() as $value) {
                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
//                    $_tmpPriceValues[$value->getId()] = Mage::helper('core')->currency($value->getPrice(true), false, false);
                    $info = array(
                        'option_id' => $value->getId(),
                        'option_value' => $value->getTitle(),
                        'option_price' => Mage::helper('core')->currency($value->getPrice(true), false, false),
                        'option_title' => $_option->getTitle(),
                        'position' => $_option->getSortOrder(),
                        'option_type_id' => $_option->getId(),
                        'option_type' => $type,
                        'is_required' => $_option->getIsRequire() == 1 ? 'YES' : 'No',
                    );

                    $this->setOptionPriceTax($info, $value->getPrice(true));
                    $infomation[] = $info;
                }
//                $priceValue = $_tmpPriceValues;
            } else if ($_option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_TEXT) {
//                $priceValue = Mage::helper('core')->currency($_option->getPrice(true), false, false);
                $info = array(
                    'option_price' => Mage::helper('core')->currency($_option->getPrice(true), false, false),
                    'option_title' => $_option->getTitle(),
                    'position' => $_option->getSortOrder(),
                    'option_type_id' => $_option->getId(),
                    'option_type' => 'text',
                    'is_required' => $_option->getIsRequire() == 1 ? 'YES' : 'No',
                );

                $this->setOptionPriceTax($info, $_option->getPrice(true));
                $infomation[] = $info;
            } else if ($_option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_DATE) {
                $info = array(
                    'option_price' => Mage::helper('core')->currency($_option->getPrice(true), false, false),
                    'option_title' => $_option->getTitle(),
                    'position' => $_option->getSortOrder(),
                    'option_type_id' => $_option->getId(),
                    'option_type' => $_option->getType(),
                    'is_required' => $_option->getIsRequire() == 1 ? 'YES' : 'No',
                );

                $this->setOptionPriceTax($info, $_option->getPrice(true));
                $infomation[] = $info;
            }
        }
	}

}

?>