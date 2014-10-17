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
class Simi_Connector_Model_Catalog_Product_Options_Grouped extends Simi_Connector_Model_Abstract {

    public $_minprice = null;

    public function getPrice($product) {
        $_taxHelper = Mage::helper('tax');
        $_minimalPriceValue = $product->getMinimalPrice();
//        $_exclTax = $_taxHelper->getPrice($product, $_minimalPriceValue);
//        $_inclTax = $_taxHelper->getPrice($product, $_minimalPriceValue, true);      
        $this->getOptions($product);    
        if ($_minimalPriceValue) {
            if ($_minimalPriceValue < $product->getFinalPrice())
                return array(
                    'min_price' => Mage::app()->getStore()->convertPrice($_minimalPriceValue, false),
                );
            else
                return array(
                    'min_price' => Mage::app()->getStore()->convertPrice($this->_minprice, false),
                );
        }
        return array();
    }

    public function getAssociatedProducts($product) {
        return $product->getTypeInstance(true)->getAssociatedProducts($product);
    }

    public function getOptions($product) {
        $information = array();
        $associatedProducts = $this->getAssociatedProducts($product);
        if (count($associatedProducts)) {
            foreach ($associatedProducts as $product) {
                if ($product->isSaleable()) {
                    if ($this->_minprice == NULL) {
                        $this->_minprice = $product->getFinalPrice();
                    } else {
                        if ($this->_minprice > $product->getFinalPrice())
                            $this->_minprice = $product->getFinalPrice();
                    }
                    $info = array(
                        'option_id' => $product->getId(),
                        'option_value' => $product->getName(),
                        'option_title' => $product->getName(),
                        'option_type' => 'text',
                        'option_price' => Mage::app()->getStore()->convertPrice($product->getFinalPrice(), false),
                    );
                    Mage::helper("connector/tax")->getProductTax($product, $info);
                    $information[] = $info;
                }
            }
        }
        //Zend_debug::dump($this->_minprice);die();
        return $information;
    }

}

?>