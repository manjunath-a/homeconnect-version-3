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
 * @category 	Simi
 * @package 	Simi_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Helper
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Helper_Tax extends Mage_Core_Helper_Abstract {

    public $_product;

    public function getProductAttribute($attribute) {
        return $this->_product->getResource()->getAttribute($attribute);
    }

    public function getProductTax($_product, &$data, $is_group_detail = false, $is_list = true) {


        $_coreHelper = Mage::helper('core');
        $_weeeHelper = Mage::helper('weee');
        $_taxHelper = Mage::helper('tax');
        /* @var $_coreHelper Mage_Core_Helper_Data */
        /* @var $_weeeHelper Mage_Weee_Helper_Data */
        /* @var $_taxHelper Mage_Tax_Helper_Data */

        $this->_product = $_product;
        $_storeId = $_product->getStoreId();
        $_store = $_product->getStore();
        $_id = $_product->getId();
        $priveV2 = array();

        if ($_product->getTypeId() == "bundle") {
            Mage::helper('connector/bundle_tax')->getProductTax($_product, $priveV2, $is_list);
            if (!$is_list) {
                Mage::helper('connector/bundle_tier')->addTier($priveV2, $this->_product);
            }
            $data['show_price_v2'] = $priveV2;
            return;
        }
        $_simplePricesTax = ($_taxHelper->displayPriceIncludingTax() || $_taxHelper->displayBothPrices());
        $_minimalPriceValue = $this->_product->getMinimalPrice();
        $_minimalPriceValue = $_store->roundPrice($_store->convertPrice($_minimalPriceValue));
        $_minimalPrice = $_taxHelper->getPrice($this->_product, $_minimalPriceValue, $_simplePricesTax);
        $_convertedFinalPrice = $_store->roundPrice($_store->convertPrice($this->_product->getFinalPrice()));
        $_specialPriceStoreLabel = $this->getProductAttribute('special_price')->getStoreLabel();


        if (!$this->_product->isGrouped()) {
            $_weeeTaxAmount = $_weeeHelper->getAmountForDisplay($this->_product);
            $_weeeTaxAttributes = $_weeeHelper->getProductWeeeAttributesForRenderer($this->_product, null, null, null, true);
            $_weeeTaxAmountInclTaxes = $_weeeTaxAmount;
            if ($_weeeHelper->isTaxable()) {
                $_weeeTaxAmountInclTaxes = $_weeeHelper->getAmountInclTaxes($_weeeTaxAttributes);
            }
            $_weeeTaxAmount = $_store->roundPrice($_store->convertPrice($_weeeTaxAmount));
            $_weeeTaxAmountInclTaxes = $_store->roundPrice($_store->convertPrice($_weeeTaxAmountInclTaxes));


            $_convertedPrice = $_store->roundPrice($_store->convertPrice($_product->getPrice()));
            $_price = $_taxHelper->getPrice($_product, $_convertedPrice);
            $_regularPrice = $_taxHelper->getPrice($_product, $_convertedPrice, $_simplePricesTax);
            $_finalPrice = $_taxHelper->getPrice($_product, $_convertedFinalPrice);
            $_finalPriceInclTax = $_taxHelper->getPrice($_product, $_convertedFinalPrice, true);
            $_weeeDisplayType = $_weeeHelper->getPriceDisplayType();

            //check final price

            if ($_finalPrice >= $_price) {
                if ($_taxHelper->displayBothPrices()) {
                    // $priveV2["is_show_price"] = false;
                    if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 0)) {						
                        $priveV2["excl_tax"] = $_price + $_weeeTaxAmount;
                        $priveV2["incl_tax"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                    } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 1)) {
                        $priveV2["excl_tax"] = $_price + $_weeeTaxAmoun;
                        $priveV2["incl_tax"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount(), false, false);
                            $wee["cop"] = "+";
                            $priveV2["weee"][] = $wee;
                        }
						
                    } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 4)) {
                        $priveV2["excl_tax"] = $_price + $_weeeTaxAmount;
                        $priveV2["incl_tax"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount(), false, false);
                            $wee["cop"] = "+";
                            $priveV2["weee"][] = $wee;
                        }
						
                    } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 2)) {// excl. + weee + final                        
                        $priveV2["excl_tax"] = $_price;
                        $priveV2["incl_tax"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount(), false, false);
                            $wee["cop"] = "/";
                            $priveV2["weee"][] = $wee;
                        }
						
                    } else {
						// Zend_debug::dump($_price);die();
                        if ($_finalPrice == $_price) {
                            $priveV2["excl_tax"] = $_price;
                        } else {
                            $priveV2["excl_tax"] = $_finalPrice;
                        }
                        $priveV2["incl_tax"] = $_finalPriceInclTax;
						
                    }
                } else {
                    if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, array(0, 1))) { // including
                        $weeeAmountToDisplay = $_taxHelper->displayPriceIncludingTax() ? $_weeeTaxAmountInclTaxes : $_weeeTaxAmount;
                        $priveV2["product_regular_price"] = $_coreHelper->currency($_price + $weeeAmountToDisplay, false, false);
                        if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 1)) {// show description
                            foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                                $wee = array();
                                $wee["name"] = $_weeeTaxAttribute->getName();
                                $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount() + ($_taxHelper->displayPriceIncludingTax() ? $_weeeTaxAttribute->getTaxAmount() : 0), false, false);
                                $wee["cop"] = "+";
                                $priveV2["weee"][] = $wee;
                            }
                        }
                    } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 4)) {
                        $priveV2["product_regular_price"] = $_price + $_weeeTaxAmount;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount(), false, false);
                            $wee["cop"] = "+";
                            $priveV2["weee"][] = $wee;
                        }
                    } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 2)) {
                        $priveV2["product_regular_price"] = $_price;
                        $weeeAmountToDisplay = $_taxHelper->displayPriceIncludingTax() ? $_weeeTaxAmountInclTaxes : $_weeeTaxAmount;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount() + ($_taxHelper->displayPriceIncludingTax() ? $_weeeTaxAttribute->getTaxAmount() : 0), false, false);
                            $wee["cop"] = "/";
                            $priveV2["weee"][] = $wee;
                        }
                    } else {
                        if ($_finalPrice == $_price) {
                            $priveV2["product_regular_price"] = $_price;
                        } else {
                            $priveV2["product_regular_price"] = $_finalPrice;
                        }
                    }
                }
            } else /* if ($_finalPrice == $_price): */ {
                $_originalWeeeTaxAmount = $_weeeHelper->getOriginalAmount($_product);
                $_originalWeeeTaxAmount = $_store->roundPrice($_store->convertPrice($_originalWeeeTaxAmount));
                if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 0)) { // including
                    // Regular Price
                    $priveV2["product_regular_price"] = $_regularPrice + $_originalWeeeTaxAmount;
                    $priveV2["special_price_label"] = $_specialPriceStoreLabel;
                    if ($_taxHelper->displayBothPrices()) {
                        //$priveV2["product_price"] = $_store->convertPrice($_regularPrice + $_originalWeeeTaxAmount, false);
                        $priveV2["excl_tax_special"] = $_finalPrice + $_weeeTaxAmount;
                        $priveV2["incl_tax_special"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                    } else {
                        //only show $_specialPriceStoreLabel
                        $priveV2["product_price"] = $_finalPrice + $_weeeTaxAmountInclTaxes;
                    }
                } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 1)) { // incl. + weee                     
                    $priveV2["product_regular_price"] = $_regularPrice + $_originalWeeeTaxAmount;
                    $priveV2["special_price_label"] = $_specialPriceStoreLabel;
                    if ($_taxHelper->displayBothPrices()) {
                        // $priveV2["product_price"] = $_store->convertPrice($_regularPrice + $_originalWeeeTaxAmount, false);
                        $priveV2["excl_tax_special"] = $_finalPrice + $_weeeTaxAmount;
                        $priveV2["incl_tax_special"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount(), false, false);
                            $wee["cop"] = "+";
                            $priveV2["weee_special"][] = $wee;
                        }
                    } else {
                        $priveV2["product_price"] = $_finalPrice + $_weeeTaxAmountInclTaxes;
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount(), false, false);
                            $wee["cop"] = "+";
                            $priveV2["weee"][] = $wee;
                        }
                    }
                } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 4)) { // incl. + weee                       
                    $priveV2["product_regular_price"] = $_regularPrice + $_originalWeeeTaxAmount;
                    //$priveV2["product_price"] = $_store->convertPrice($_regularPrice + $_originalWeeeTaxAmount, false);
                    $priveV2["special_price_label"] = $_specialPriceStoreLabel;
                    $priveV2["excl_tax_special"] = $_finalPrice + $_weeeTaxAmount;
                    $priveV2["incl_tax_special"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                    foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                        $wee = array();
                        $wee["name"] = $_weeeTaxAttribute->getName();
                        $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount(), false, false);
                        $wee["cop"] = "+";
                        $priveV2["weee"][] = $wee;
                    }
                } elseif ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 2)) { // excl. + weee + final                   
                    $priveV2["product_regular_price"] = $_regularPrice;
                    // $priveV2["product_price"] = $_store->convertPrice($_regularPrice, false);
                    $priveV2["special_price_label"] = $_specialPriceStoreLabel;
                    $priveV2["excl_tax_special"] = $_finalPrice + $_weeeTaxAmount;
                    $priveV2["incl_tax_special"] = $_finalPriceInclTax + $_weeeTaxAmountInclTaxes;
                    foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                        $wee = array();
                        $wee["name"] = $_weeeTaxAttribute->getName();
                        $wee["amount"] = $_coreHelper->currency($_weeeTaxAttribute->getAmount(), false, false);
                        $wee["cop"] = "/";
                        $priveV2["weee_special"][] = $wee;
                    }
                } else { // excl.                    
                    $priveV2["product_regular_price"] = $_regularPrice;
                    $priveV2["special_price_label"] = $_specialPriceStoreLabel;
                    if ($_taxHelper->displayBothPrices()) {
                        // $priveV2["product_price"] = $_store->convertPrice($_regularPrice, false);
                        $priveV2["excl_tax_special"] = $_finalPrice;
                        $priveV2["incl_tax_special"] = $_finalPriceInclTax;
                    } else {
                        $priveV2["product_price"] = $_finalPrice;
                    }
                }
            }

            if ($this->getDisplayMinimalPrice() && $_minimalPriceValue && $_minimalPriceValue < $_convertedFinalPrice) {
                if ($is_list) {
                    $_minimalPriceDisplayValue = $_minimalPrice;
                    if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, array(0, 1, 4))) {
                        $_minimalPriceDisplayValue = $_minimalPrice + $_weeeTaxAmount;
                    }
                    $priveV2["minimal_price_label"] = Mage::helper("core")->__('As low as');
                    $priveV2["minimal_price"] = $_minimalPriceDisplayValue;
                }
            }
        } else {// group
            if (!$is_group_detail) {
                $showMinPrice = $this->getDisplayMinimalPrice();
                //  $priveV2["is_show_price"] = false;
                if ($showMinPrice && $_minimalPriceValue) {
                    $_exclTax = $_taxHelper->getPrice($_product, $_minimalPriceValue);
                    $_inclTax = $_taxHelper->getPrice($_product, $_minimalPriceValue, false);
                    $price = $showMinPrice ? $_minimalPriceValue : 0;
                } else {
                    $price = $_convertedFinalPrice;
                    $_exclTax = 0;
                    $_inclTax = 0;
                }

                if ($price) {
                    if ($showMinPrice) {
                        $priveV2["minimal_price_label"] = Mage::helper("core")->__('Starting at');
                        // $priveV2["is_show_price"] = false;
                    }
                    if ($_taxHelper->displayBothPrices()) {
                        $priveV2["excl_tax_minimal"] = $_exclTax;
                        $priveV2["incl_tax_minimal"] = $_inclTax;
                    } else {
                        $_showPrice = $_inclTax;
                        if (!$_taxHelper->displayPriceIncludingTax()) {
                            $_showPrice = $_exclTax;                           
                        }
						$priveV2["minimal_price"] = $_showPrice;
                    }
                }
            } else {
                $priveV2['product_regular_price'] = 0;
            }
        }
        if (!$is_list) {
            if ($this->_product->getTypeId() == "bundle") {

                Mage::helper('connector/bundle_tier')->addTier($priveV2, $this->_product);
            } else {
                Mage::helper('connector/tier')->addTier($priveV2, $this->_product);
            }
//            Mage::helper('connector/bundle_tax')->getPriceConfig($this->_product, $priveV2);
        }
        $data['show_price_v2'] = $priveV2;
    }

    public function getDisplayMinimalPrice() {
        return $this->_product->getMinimalPrice();
    }

}