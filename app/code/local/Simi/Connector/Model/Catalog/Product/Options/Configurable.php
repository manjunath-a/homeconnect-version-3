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
class Simi_Connector_Model_Catalog_Product_Options_Configurable extends Simi_Connector_Model_Abstract {

    public $_defaultTax;
    public $_currentTax;
    public $_includeTax;
    public $_showIncludeTax;
    public $_showBothPrices;

    public function getAttributes($product) {
        return $product->getTypeInstance(true)->getConfigurableAttributes($product);
    }

    public function getAllowProducts($_product) {
        if (!$this->hasAllowProducts()) {
            $products = array();
            $skipSaleableCheck = true;
            if (version_compare(Mage::getVersion(), '1.7.0.0', '>=') === true) {
                $skipSaleableCheck = Mage::helper('catalog/product')->getSkipSaleableCheck();
            }
            $allProducts = $_product->getTypeInstance(true)
                    ->getUsedProducts(null, $_product);
            foreach ($allProducts as $product) {
                if ($product->isSaleable() || $skipSaleableCheck) {
                    $products[] = $product;
                }
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }

    public function getOptions($_product) {
        $options = array();
        $currentProduct = $_product;
        $products = $this->getAllowProducts($_product);
        $attributes = $this->getAttributes($_product);
        $taxHelper = Mage::helper('tax');
        $taxCalculation = Mage::getSingleton('tax/calculation');
        if (!$taxCalculation->getCustomer() && Mage::registry('current_customer')) {
            $taxCalculation->setCustomer(Mage::registry('current_customer'));
        }
        if (version_compare(Mage::getVersion(), '1.9.0.0', '>') === true) {
            $_request = $taxCalculation->getDefaultRateRequest();
        } else {
            $_request = $taxCalculation->getRateRequest(false, false, false);
        }
        $_request->setProductClassId($currentProduct->getTaxClassId());
        $this->_defaultTax = $taxCalculation->getRate($_request);
        // die('2');
        $_request = $taxCalculation->getRateRequest();
        $_request->setProductClassId($currentProduct->getTaxClassId());
        $this->_currentTax = $taxCalculation->getRate($_request);
        // die('3');
        $this->_includeTax = $taxHelper->priceIncludesTax();
        $this->_showIncludeTax = $taxHelper->displayPriceIncludingTax();
        $this->_showBothPrices = $taxHelper->displayBothPrices();

        $information = array();
        foreach ($products as $product) {
            $productId = $product->getId();
            foreach ($attributes as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();
                $attributeValue = $product->getData($productAttribute->getAttributeCode());
                // if (!in_array($attributeValue, $list_value))
                // $list_value[] = $attributeValue;
                if (!isset($options[$productAttributeId])) {
                    $options[$productAttributeId] = array();
                }

                if (!isset($options[$productAttributeId][$attributeValue])) {
                    $options[$productAttributeId][$attributeValue] = array();
                }
                $options[$productAttributeId][$attributeValue][] = $productId;
            }
        }

        foreach ($attributes as $attribute) {
            $attributeId = $attribute->getProductAttribute()->getId();
            $prices = $attribute->getPrices();
            if (is_array($prices)) {
                foreach ($prices as $value) {
                    // if (in_array($value['value_index'], $list_value)) {
                    //-------------get dependence_option_ids----------------------
                    // Zend_debug::dump($attributeId);die();
                    if (!$this->_validateAttributeValue($attributeId, $value, $options)) {
                        continue;
                    }
                    $currentProduct->setConfigurablePrice(
                            $this->_preparePrice($value['pricing_value'], $value['is_percent'], $currentProduct)
                    );
                    $currentProduct->setParentId(true);
                    Mage::dispatchEvent(
                            'catalog_product_type_configurable_price', array('product' => $currentProduct)
                    );
                    $configurablePrice = $currentProduct->getConfigurablePrice();

                    $productsIndex = array();
                    if (isset($options[$attributeId][$value['value_index']])) {
                        $productsIndex = $options[$attributeId][$value['value_index']];
                    }
                    //---------------end-----------------------
                    $option_price = $configurablePrice != NULL ? $configurablePrice : '0';

                    $infor = array(
                        'option_id' => $value['value_index'],
                        'option_value' => $value['label'],
                        'option_price' => $option_price,
                        'option_title' => $attribute->getLabel(),
                        'position' => '0',
                        'option_type_id' => $attribute->getProductAttribute()->getId(),
                        'option_type' => 'single',
                        'is_required' => 'YES',
                        'dependence_option_ids' => $productsIndex
                    );
                    $this->setOptionPriceTax($infor['option_price'], $infor);
                    $information[] = $infor;
                }
                // }
            }
			if(!is_null($_product->getOptions()) && count($_product->getOptions())){
				Mage::getModel('connector/catalog_product_options_simple')->addOption($information, $_product);				
			}		
        }
        return $information;
    }

    protected function _validateAttributeValue($attributeId, &$value, &$options) {
        if (isset($options[$attributeId][$value['value_index']])) {
            return true;
        }

        return false;
    }

    protected function _preparePrice($price, $isPercent = false, $product) {
        if ($isPercent && !empty($price)) {
            $price = $product->getFinalPrice() * $price / 100;
        }

        return $this->_registerJsPrice($this->_convertPrice($price, true));
    }

    protected function _registerJsPrice($price) {
        return str_replace(',', '.', $price);
    }

    protected function _convertPrice($price, $round = false) {
        if (empty($price)) {
            return 0;
        }

        $price = $this->getCurrentStore()->convertPrice($price);
        if ($round) {
            $price = $this->getCurrentStore()->roundPrice($price);
        }

        return $price;
    }

    public function getCurrentStore() {
        return Mage::app()->getStore();
    }

    public function setOptionPriceTax($price, &$info) {
        $excl = 0;
        $incl = 0;
        if ($this->_includeTax) {
            $tax = $price / (100 + $this->_defaultTax) * $this->_defaultTax;
            $excl = $price - $tax;
            $incl = $excl * (1 + ($this->_currentTax / 100));
        } else {
            $tax = $price * ($this->_currentTax / 100);
            $excl = $price;
            $incl = $excl + $tax;
        }

        if ($this->_showIncludeTax || $this->_showBothPrices) {
            $price = $incl;
        } else {
            $price = $excl;
        }

        if ($price) {
            if ($this->_showBothPrices) {
                $info['option_price'] = round($excl, 2);
                $info['option_price_incl_tax'] = round($price, 2);
            } else {
                $info['option_price'] = round($price, 2);
            }
        }
    }

}

?>