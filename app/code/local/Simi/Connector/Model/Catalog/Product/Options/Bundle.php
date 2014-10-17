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
class Simi_Connector_Model_Catalog_Product_Options_Bundle extends Simi_Connector_Model_Abstract {

    public function getPrice($product) {
        $productPrice = $product->getPriceModel();
        if (version_compare(Mage::getVersion(), '1.6.2.0', '>=') === true) {
            list($_minimalPriceTax, $_maximalPriceTax) = $productPrice->getTotalPrices($product, null, null, false);
        } else {
            list($_minimalPriceTax, $_maximalPriceTax) = $productPrice->getPrices($product, null, null, false);
        }

        if ($product->getPriceType() == 1) {
            $_weeeTaxAmount = Mage::helper('weee')->getAmount($product);
            $_weeeTaxAmountInclTaxes = $_weeeTaxAmount;
            if (Mage::helper('weee')->isTaxable()) {
                $_attributes = Mage::helper('weee')->getProductWeeeAttributesForRenderer($product, null, null, null, true);
                $_weeeTaxAmountInclTaxes = Mage::helper('weee')->getAmountInclTaxes($_attributes);
            }
            if ($_weeeTaxAmount && Mage::helper('weee')->typeOfDisplay($product, array(0, 1, 4))) {
                $_minimalPriceTax += $_weeeTaxAmount;
                $_minimalPriceInclTax += $_weeeTaxAmountInclTaxes;
            }
            if ($_weeeTaxAmount && Mage::helper('weee')->typeOfDisplay($product, 2)) {
                $_minimalPriceInclTax += $_weeeTaxAmountInclTaxes;
            }
        }
        return array(
            'min_price' => Mage::app()->getStore()->convertPrice($_minimalPriceTax, false),
            'max_price' => Mage::app()->getStore()->convertPrice($_maximalPriceTax, false),
        );
    }

    public function getAttributes($product) {
        $typeInstance = $product->getTypeInstance(true);
        $typeInstance->setStoreFilter($product->getStoreId(), $product);
        $optionCollection = $typeInstance->getOptionsCollection($product);

        $selectionCollection = $typeInstance->getSelectionsCollection(
                $typeInstance->getOptionsIds($product), $product
        );

        return $optionCollection->appendSelections($selectionCollection, false, false);
    }

    public function getOptions($product) {
        $attributes = $this->getAttributes($product);
        $information = array();
        $coreHelper = Mage::helper('core');
        foreach ($attributes as $_attribute) {
            $optiondId = $_attribute->getId();
            $title = $_attribute->getTitle();
            $position = $_attribute->getPosition();
            $type = $_attribute->getType();
            if ($type == 'multi' || $type == 'checkbox')
                $type = 'multi';
            else
                $type = 'single';
            $require = $_attribute->getRequired();
            foreach ($_attribute->getSelections() as $_selection) {
                $selectionId = $_selection->getSelectionId();
                $selectionName = $_selection->getName();
                $price = $product->getPriceModel()->getSelectionPreFinalPrice($product, $_selection, 1);
                $this->setFormatProduct($_selection);
                $infor = array(
                    'option_id' => $selectionId,
                    'option_value' => $selectionName,
                    'option_price' => $price,
                    'option_title' => $title,
                    'option_type_id' => $optiondId,
                    'option_type' => $type,
                    'position' => $position,
                    'is_required' => $require == 1 ? 'YES' : 'No',
                );
                $this->formatPriceString($price, $product, $infor);
                $information[] = $infor;
            }
        }
        return $information;
    }

    public function formatPriceString($price, $product, &$information) {
        $taxHelper = Mage::helper('tax');
        $coreHelper = Mage::helper('core');
        $currentProduct = $product;
        if ($currentProduct->getPriceType() == Mage_Bundle_Model_Product_Price::PRICE_TYPE_DYNAMIC
                && $this->getFormatProduct()
        ) {
            $product = $this->getFormatProduct();
        } else {
            $product = $currentProduct;
        }

        $priceTax = $taxHelper->getPrice($product, $price);
        $priceIncTax = $taxHelper->getPrice($product, $price, true);

        $formated = $coreHelper->currencyByStore($priceTax, $product->getStore(), false, false);
        $information['option_price'] = $formated;
        if ($taxHelper->displayBothPrices()) {
            $information['option_price_incl_tax'] =
                    $coreHelper->currencyByStore($priceIncTax, $product->getStore(), false, false);
        }     
    }

}

?>