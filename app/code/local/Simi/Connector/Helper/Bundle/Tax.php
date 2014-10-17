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
class Simi_Connector_Helper_Bundle_Tax extends Mage_Core_Helper_Abstract {

    public $_product;

    public function setProduct($product) {
        $this->_product = $product;
    }

    public function getProduct() {
        return $this->_product;
    }

    public function displayBothPrices() {
        $product = $this->getProduct();
        if ($product->getPriceType() == Mage_Bundle_Model_Product_Price::PRICE_TYPE_DYNAMIC &&
                $product->getPriceModel()->getIsPricesCalculatedByIndex() !== false) {
            return false;
        }
        return Mage::helper('tax')->displayBothPrices(Mage::app()->getStore()->getId());
    }

    public function getProductTax($_product, &$data, $is_list = true) {
        $this->setProduct($_product);
        $_coreHelper = Mage::helper('core');
        $_weeeHelper = Mage::helper('weee');
        $_taxHelper = Mage::helper('tax');

        $_priceModel = $_product->getPriceModel();

        list($_minimalPriceTax, $_maximalPriceTax) = $_priceModel->getTotalPrices($_product, null, null, false);
        list($_minimalPriceInclTax, $_maximalPriceInclTax) = $_priceModel->getTotalPrices($_product, null, true, false);

        $_weeeTaxAmount = 0;

        //   $data["is_show_price"] = false;

        if ($_product->getPriceType() == 1) {
            $_weeeTaxAmount = $_weeeHelper->getAmountForDisplay($_product);
            $_weeeTaxAmountInclTaxes = $_weeeTaxAmount;
            if ($_weeeHelper->isTaxable()) {
                $_attributes = $_weeeHelper->getProductWeeeAttributesForRenderer($_product, null, null, null, true);
                $_weeeTaxAmountInclTaxes = $_weeeHelper->getAmountInclTaxes($_attributes);
            }
            if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, array(0, 1, 4))) {
                $_minimalPriceTax += $_weeeTaxAmount;
                $_minimalPriceInclTax += $_weeeTaxAmountInclTaxes;
                $_maximalPriceTax += $_weeeTaxAmount;
                $_maximalPriceInclTax += $_weeeTaxAmountInclTaxes;
            }
            if ($_weeeTaxAmount && $_weeeHelper->typeOfDisplay($_product, 2)) {
                $_minimalPriceInclTax += $_weeeTaxAmountInclTaxes;
                $_maximalPriceInclTax += $_weeeTaxAmountInclTaxes;
            }

            if ($_weeeHelper->typeOfDisplay($_product, array(1, 2, 4))) {
                $_weeeTaxAttributes = $_weeeHelper->getProductWeeeAttributesForRenderer($_product, null, null, null, true);
            }
        }

        if ($_product->getPriceView()) {
            $data["minimal_price_label"] = Mage::helper("core")->__('As low as');
            if ($this->displayBothPrices()) {
                $data["excl_tax_minimal"] = abs($_coreHelper->currency($_minimalPriceTax, false, false));
                $data["incl_tax_minimal"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                    $_weeeSeparator = '';
                    foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                        if ($_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                            $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                        } else {
                            $amount = $_weeeTaxAttribute->getAmount();
                        }
                        $wee = array();
                        $wee["name"] = $_weeeTaxAttribute->getName();
                        $wee["amount"] = $_coreHelper->currency($amount, false, false);
                        $wee["cop"] = "+";
                        $data["weee_minimal"][] = $wee;
                    }
                }
            } else {
                if ($_taxHelper->displayPriceIncludingTax()) {
                    $data["incl_tax_minimal"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                } else {
                    $data["excl_tax_minimal"] = abs($_coreHelper->currency($_minimalPriceTax, false, false));
                }
                if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                    $_weeeSeparator = '';
                    foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                        if ($_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                            $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                        } else {
                            $amount = $_weeeTaxAttribute->getAmount();
                        }
                        $wee = array();
                        $wee["name"] = $_weeeTaxAttribute->getName();
                        $wee["amount"] = $_coreHelper->currency($amount, false, false);
                        $wee["cop"] = "+";
                        $data["weee_minimal"][] = $wee;
                    }
                }
                if ($_weeeHelper->typeOfDisplay($_product, 2) && $_weeeTaxAmount) {
                    $data["incl_tax_minimal"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                }
            }
        } else {
            if ($_minimalPriceTax <> $_maximalPriceTax) {
                $data['price_tax_from_label'] = Mage::helper('core')->__('From');
                if ($this->displayBothPrices()) {
                    $data["excl_tax_from"] = abs($_coreHelper->currency($_minimalPriceTax, false, false));
                    $data["incl_tax_from"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if ($_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee"][] = $wee;
                        }
                    }
                } else {
                    if ($_taxHelper->displayPriceIncludingTax()) {
                        $data["incl_tax_from"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                    } else {
                        $data["excl_tax_from"] = abs($_coreHelper->currency($_minimalPriceTax, false, false));
                    }
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if ($_taxHelper->displayPriceIncludingTax() || $_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee"][] = $wee;
                        }
                    }
                    if ($_weeeHelper->typeOfDisplay($_product, 2) && $_weeeTaxAmount) {
                        $data["incl_tax_from"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                    }
                }
                //to 
                $data['price_tax_to_label'] = Mage::helper('core')->__('To');
                if ($this->displayBothPrices()) {
                    $data["excl_tax_to"] = abs($_coreHelper->currency($_maximalPriceTax, false, false));
                    $data["incl_tax_to"] = abs($_coreHelper->currency($_maximalPriceInclTax, false, false));
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if ($_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee"][] = $wee;
                        }
                    }
                } else {
                    if ($_taxHelper->displayPriceIncludingTax()) {
                        $data["incl_tax_to"] = abs($_coreHelper->currency($_maximalPriceInclTax, false, false));
                    } else {
                        $data["excl_tax_to"] = abs($_coreHelper->currency($_maximalPriceTax, false, false));
                    }
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if ($_taxHelper->displayPriceIncludingTax() || $_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee"][] = $wee;
                        }
                    }
                    if ($_weeeHelper->typeOfDisplay($_product, 2) && $_weeeTaxAmount) {
                        $data["incl_tax_to"] = abs($_coreHelper->currency($_maximalPriceInclTax, false, false));
                    }
                }
            } else {
                if ($this->displayBothPrices()) {
                    $data["excl_tax"] = abs($_coreHelper->currency($_minimalPriceTax, false, false));
                    $data["incl_tax"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if ($_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee"][] = $wee;
                        }
                    }
                } else {
                    $data["excl_tax"] = abs($_coreHelper->currency($_minimalPriceTax));
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && $_weeeHelper->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if ($_taxHelper->displayPriceIncludingTax() || $_weeeHelper->typeOfDisplay($_product, array(2, 4))) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }
                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = $_coreHelper->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee"][] = $wee;
                        }
                    }
                    if ($_weeeHelper->typeOfDisplay($_product, 2) && $_weeeTaxAmount) {
                        if ($_taxHelper->displayPriceIncludingTax()) {
                            $data["incl_tax"] = abs($_coreHelper->currency($_minimalPriceInclTax, false, false));
                        } else {
                            $data["excl_tax"] = abs($_coreHelper->currency($_minimalPriceTax + $_weeeTaxAmount, false, false));
                        }
                    }
                }
            }
        }
        if (!$is_list) {
            $this->getPriceConfig($_product, $data);
            // if (!isset($data['product_price_config'])) {
                // if ($data["excl_tax_config"] == 0) {
                    // $data["excl_tax_config"] = $_minimalPriceTax;
                // }
                // if ($data["incl_tax_config"] == 0) {
                    // $data["incl_tax_config"] = $_minimalPriceTax;
                // }
            // } else {
                // if ($data['product_price_config'] > $_minimalPriceTax) {
                    // $data['product_price_config'] = $_minimalPriceTax;
                // }
            // }
        }
    }

    public function getPriceConfig($_product, &$data) {

        $_finalPrice = $_product->getFinalPrice();
        $_finalPriceInclTax = $_product->getFinalPrice();
        $_weeeTaxAmount = 0;

        if ($_product->getPriceType() == 1) {
            $_weeeTaxAmount = Mage::helper('weee')->getAmount($_product);
            if (Mage::helper('weee')->typeOfDisplay($_product, array(1, 2, 4))) {
                $_weeeTaxAttributes = Mage::helper('weee')->getProductWeeeAttributesForRenderer($_product, null, null, null, true);
            }
        }
        $isMAPTypeOnGesture = Mage::helper('catalog')->isShowPriceOnGesture($_product);
        $canApplyMAP = Mage::helper('catalog')->canApplyMsrp($_product);
        if ($_product->getCanShowPrice() !== false) {
            $data['price_as_configured_label'] = Mage::helper('bundle')->__('Price as configured');
            if (!$isMAPTypeOnGesture && $canApplyMAP) {
                
            }

            if (Mage::helper('tax')->displayBothPrices()) {
                if (!$canApplyMAP) {
                    $data["excl_tax_config"] = Mage::helper('core')->currency($_finalPrice, false, false);
                    if ($data["excl_tax_config"] != $data["excl_tax_from"]) {
                        $data["excl_tax_config"] = $data["excl_tax_from"];
                    }
                }
                if ($_weeeTaxAmount && $_product->getPriceType() == 1 && Mage::helper('weee')->typeOfDisplay($_product, array(2, 1, 4))) {
                    $_weeeSeparator = '';
                    foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                        if (Mage::helper('weee')->typeOfDisplay($_product, array(2, 4))) {
                            $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                        } else {
                            $amount = $_weeeTaxAttribute->getAmount();
                        }

                        $wee = array();
                        $wee["name"] = $_weeeTaxAttribute->getName();
                        $wee["amount"] = Mage::helper('core')->currency($amount, false, false);
                        $wee["cop"] = "+";
                        $data["weee_config"][] = $wee;
                    }
                }
                if (!$canApplyMAP) {
                    $data["incl_tax_config"] = Mage::helper('core')->currency($_finalPriceInclTax, false, false);					
                    if ($data["incl_tax_config"] != $data["incl_tax_from"]) {
                        $data["incl_tax_config"] = $data["incl_tax_from"];
                    }
                }
            } else {
                if (!$canApplyMAP) {
                    $data['product_price_config'] = Mage::helper('core')->currency($_finalPrice, false, false);
                    if ($_weeeTaxAmount && $_product->getPriceType() == 1 && Mage::helper('weee')->typeOfDisplay($_product, array(2, 1, 4))) {
                        $_weeeSeparator = '';
                        foreach ($_weeeTaxAttributes as $_weeeTaxAttribute) {
                            if (Mage::helper('tax')->displayPriceIncludingTax()) {
                                $amount = $_weeeTaxAttribute->getAmount() + $_weeeTaxAttribute->getTaxAmount();
                            } else {
                                $amount = $_weeeTaxAttribute->getAmount();
                            }

                            $wee = array();
                            $wee["name"] = $_weeeTaxAttribute->getName();
                            $wee["amount"] = Mage::helper('core')->currency($amount, false, false);
                            $wee["cop"] = "+";
                            $data["weee_config"][] = $wee;
                        }
                    }
					// Zend_debug::dump($data);die();					
					if($data['product_price_config'] != 0 && isset($data["incl_tax_from"])){						
						if ($data['product_price_config'] < $data["incl_tax_from"]){
							$data['product_price_config'] = $data['incl_tax_from'];
						}
					}					
                }
            }
        }
    }

}