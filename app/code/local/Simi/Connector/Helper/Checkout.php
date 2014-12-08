<?php

/**
 * Simi
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
class Simi_Connector_Helper_Checkout extends Mage_Core_Helper_Abstract {

    public function soptName($name) {
        $name = explode(" ", $name, 2);
        // Zend_debug::dump($name);die();
        $change_name = array(
            'first_name' => $name[0],
            'last_name' => count($name) == 1 ? $name[0] : $name[1],
        );
        return $change_name;
    }

    public function convertBillingAddress($data) {
        $customerAddressId = false;
        if (isset($data->customer_id) && $data->customer_id != '') {
            $customer = Mage::getModel('customer/customer')->load($data['customer_id']);
            $customerAddressId = $customer->getDefaultBilling();
        }
        $name = $this->soptName($data->billingAddress->name);
        $billing = array();
        $billing['address_id'] = Mage::getModel('connector/checkout_address')->getAddressBilling()->getId();
        $billing['firstname'] = $name['first_name'];
        $billing['lastname'] = $name['last_name'];
        $billing['company'] = isset($data->billingAddress->company) == true ? $data->billingAddress->company : '';
        $billing['street'] = array($data->billingAddress->street, '');
        $billing['city'] = $data->billingAddress->city;
        $billing['region_id'] = isset($data->billingAddress->state_id) == true ? $data->billingAddress->state_id : '';
        $billing['region'] = isset($data->billingAddress->state_name) == true ? $data->billingAddress->state_name : '';
        $billing['postcode'] = $data->billingAddress->zip;
        $billing['country_id'] = $data->billingAddress->country_code;
        $billing['telephone'] = isset($data->billingAddress->phone) == true ? $data->billingAddress->phone : '';
        $billing['fax'] = isset($data->billingAddress->fax) == true ? $data->billingAddress->fax : '';
        $billing['prefix'] = isset($data->billingAddress->prefix) == true ? $data->billingAddress->prefix : '';
        $billing['suffix'] = isset($data->billingAddress->suffix) == true ? $data->billingAddress->suffix : '';
        $billing['dob'] = isset($data->billingAddress->dob) == true ? $data->billingAddress->dob : '';
        $billing['taxvat'] = isset($data->billingAddress->taxvat) == true ? $data->billingAddress->taxvat : '';
		$billing['vat_id'] = isset($data->billingAddress->vat_id) == true ? $data->billingAddress->vat_id : '';
        $billing['gender'] = isset($data->billingAddress->gender) == true ? $data->billingAddress->gender : Mage::getResourceSingleton('customer/customer')->getAttribute('gender')->getSource()->getOptionId('Male');
        $billing['month'] = isset($data->billingAddress->month) == true ? $data->billingAddress->month : '';
        $billing['day'] = isset($data->billingAddress->day) == true ? $data->billingAddress->day : '';
        $billing['year'] = isset($data->billingAddress->year) == true ? $data->billingAddress->year : '';
        $billing['save_in_address_book'] = '1';
        $billing['email'] = $data->billingAddress->email;
        $billing['use_for_shipping'] = '0';
        $billing['customer_address_id'] = isset($data->billingAddress->address_id) ? $data->billingAddress->address_id : '';
        $billing['customer_password'] = isset($data->customer_password) == true ? $data->customer_password : '';
        $billing['confirm_password'] = isset($data->confirm_password) == true ? $data->confirm_password : '';
        return $billing;
    }

    public function convertShippingAddress($data) {
        $customerAddressId = false;
        if (isset($data->customer_id) && $data->customer_id != '') {
            $customer = Mage::getModel('customer/customer')->load($data['customer_id']);
            $customerAddressId = $customer->getDefaultShipping();
        }

        $name = $this->soptName($data->shippingAddress->name);
        $shipping = array();
        $shipping['address_id'] = Mage::getModel('connector/checkout_address')->getAddressShipping()->getId();
        $shipping['firstname'] = $name['first_name'];
        $shipping['lastname'] = $name['last_name'];
        $shipping['company'] = isset($data->shippingAddress->company) == true ? $data->shippingAddress->company : '';
        $shipping['street'] = array($data->shippingAddress->street, '');
        $shipping['city'] = $data->shippingAddress->city;
        $shipping['region_id'] = isset($data->shippingAddress->state_id) == true ? $data->shippingAddress->state_id : '';
        $shipping['region'] = isset($data->shippingAddress->state_name) == true ? $data->shippingAddress->state_name : '';
        $shipping['postcode'] = $data->shippingAddress->zip;
        $shipping['country_id'] = $data->shippingAddress->country_code;
        $shipping['telephone'] = isset($data->shippingAddress->phone) == true ? $data->shippingAddress->phone : '';
        $shipping['fax'] = isset($data->shippingAddress->fax) == true ? $data->shippingAddress->fax : '';
        $shipping['prefix'] = isset($data->shippingAddress->prefix) == true ? $data->shippingAddress->prefix : '';
        $shipping['suffix'] = isset($data->shippingAddress->suffix) == true ? $data->shippingAddress->suffix : '';
        $shipping['dob'] = isset($data->shippingAddress->dob) == true ? $data->shippingAddress->dob : '';
        $shipping['taxvat'] = isset($data->shippingAddress->taxvat) == true ? $data->shippingAddress->taxvat : '';
		$shipping['vat_id'] = isset($data->shippingAddress->vat_id) == true ? $data->shippingAddress->vat_id : '';
        $shipping['gender'] = isset($data->shippingAddress->gender) == true ? $data->shippingAddress->gender : Mage::getResourceSingleton('customer/customer')->getAttribute('gender')->getSource()->getOptionId('Male');
        $shipping['month'] = isset($data->shippingAddress->month) == true ? $data->shippingAddress->month : '';
        $shipping['day'] = isset($data->shippingAddress->day) == true ? $data->shippingAddress->day : '';
        $shipping['year'] = isset($data->shippingAddress->year) == true ? $data->shippingAddress->year : '';
        $shipping['save_in_address_book'] = '1';
        $shipping['email'] = $data->shippingAddress->email;
        //$shipping['method'] = $data['shipment_method_id'];
        $shipping['customer_address_id'] = isset($data->shippingAddress->address_id) ? $data->shippingAddress->address_id : '';
        return $shipping;
    }

    public function getAgreements() {
        if (!Mage::getStoreConfigFlag('checkout/options/enable_agreements')) {
            $agreements = array();
            return $agreements;
        } else {
            $agreements = Mage::getModel('checkout/agreement')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addFieldToFilter('is_active', 1);
            return $agreements;
        }
    }

    public function convertOptionsCart($options) {
        $data = array();
        foreach ($options as $option) {
            $data[] = array(
                'option_title' => $option['label'],
                'option_value' => $option['value'],
                'option_price' => isset($option['price']) == true ? $option['price'] : 0,
            );
        }
        return $data;
    }

    public function getBundleOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item) {
        $options = array();
        $product = $item->getProduct();
        /**
         * @var Mage_Bundle_Model_Product_Type
         */
        $typeInstance = $product->getTypeInstance(true);

        // get bundle options
        $optionsQuoteItemOption = $item->getOptionByCode('bundle_option_ids');
        $bundleOptionsIds = $optionsQuoteItemOption ? unserialize($optionsQuoteItemOption->getValue()) : array();
        if ($bundleOptionsIds) {
            /**
             * @var Mage_Bundle_Model_Mysql4_Option_Collection
             */
            $optionsCollection = $typeInstance->getOptionsByIds($bundleOptionsIds, $product);

            // get and add bundle selections collection
            $selectionsQuoteItemOption = $item->getOptionByCode('bundle_selection_ids');

            $selectionsCollection = $typeInstance->getSelectionsByIds(
                    unserialize($selectionsQuoteItemOption->getValue()), $product
            );

            $bundleOptions = $optionsCollection->appendSelections($selectionsCollection, true);
            foreach ($bundleOptions as $bundleOption) {
                if ($bundleOption->getSelections()) {


                    $bundleSelections = $bundleOption->getSelections();

                    foreach ($bundleSelections as $bundleSelection) {
                        $check = array();
                        $qty = Mage::helper('bundle/catalog_product_configuration')->getSelectionQty($product, $bundleSelection->getSelectionId()) * 1;
                        if ($qty) {
                            $check[] = $qty . ' x ' . $this->escapeHtml($bundleSelection->getName());
                            $option = array(
                                'option_title' => $bundleOption->getTitle(),
                                'option_value' => $qty . ' x ' . $this->escapeHtml($bundleSelection->getName()),
                                'option_price' =>Mage::helper('core')->currency(Mage::helper('bundle/catalog_product_configuration')->getSelectionFinalPrice($item, $bundleSelection), false),
                            );
                        }
                    }
                    if ($check)
                        $options[] = $option;
                }
            }
        }

        return $options;
    }

    /**
     * Retrieves product options list
     *
     * @param Mage_Catalog_Model_Product_Configuration_Item_Interface $item
     * @return array
     */
    public function getOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item) {
        return array_merge(
                        $this->getBundleOptions($item), $this->convertOptionsCart(Mage::helper('catalog/product_configuration')->getCustomOptions($item))
        );
    }

    /**
     *  for magento < 1.5.0.0
     * @param Mage_Sales_Model_Quote_Item $item
     * @return type
     */
    public function getUsedProductOption(Mage_Sales_Model_Quote_Item $item) {
        $typeId = $item->getProduct()->getTypeId();
        switch ($typeId) {
            case Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE:
                return $this->getConfigurableOptions($item);
                break;
            case Mage_Catalog_Model_Product_Type_Grouped::TYPE_CODE:
                return $this->getGroupedOptions($item);
                break;
        }

        return $this->getCustomOptions($item);
    }

    public function getConfigurableOptions(Mage_Sales_Model_Quote_Item $item) {
        $product = $item->getProduct();

        $attributes = $product->getTypeInstance(true)
                ->getSelectedAttributesInfo($product);
        $options = array_merge($attributes, $this->getCustomOptions($item));
        return $this->convertOptionsCart($options);
    }

    public function getGroupedOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item) {
        return;
    }

    public function getCustomOptions(Mage_Sales_Model_Quote_Item $item) {
        $options = array();
        $product = $item->getProduct();
        if ($optionIds = $item->getOptionByCode('option_ids')) {
            $options = array();
            foreach (explode(',', $optionIds->getValue()) as $optionId) {
                if ($option = $product->getOptionById($optionId)) {

                    $quoteItemOption = $item->getOptionByCode('option_' . $option->getId());

                    $group = $option->groupFactory($option->getType())
                            ->setOption($option)
                            ->setQuoteItemOption($quoteItemOption);

                    $options[] = array(
                        'label' => $option->getTitle(),
                        'value' => $group->getFormattedOptionValue($quoteItemOption->getValue()),
                        'print_value' => $group->getPrintableOptionValue($quoteItemOption->getValue()),
                        'option_id' => $option->getId(),
                        'option_type' => $option->getType(),
                        'custom_view' => $group->isCustomizedView()
                    );
                }
            }
        }
        if ($addOptions = $item->getOptionByCode('additional_options')) {
            $options = array_merge($options, unserialize($addOptions->getValue()));
        }
        return $this->convertOptionsCart($options);
    }

    public function displayBothTaxSub() {
        return Mage::getSingleton('tax/config')->displayCartSubtotalBoth(Mage::app()->getStore());
    }

    public function includeTaxGrand($total) {
        if ($total->getAddress()->getGrandTotal()) {
            return Mage::getSingleton('tax/config')->displayCartTaxWithGrandTotal(Mage::app()->getStore());
        }
        return false;
    }

    public function getTotalExclTaxGrand($total) {
        $excl = $total->getAddress()->getGrandTotal() - $total->getAddress()->getTaxAmount();
        $excl = max($excl, 0);
        return $excl;
    }

    public function displayBothTaxShipping() {
        return Mage::getSingleton('tax/config')->displayCartShippingBoth(Mage::app()->getStore());
    }

    public function displayIncludeTaxShipping() {
        return Mage::getSingleton('tax/config')->displayCartShippingInclTax(Mage::app()->getStore());
    }

    public function getShippingIncludeTax($total) {
        return $total->getAddress()->getShippingInclTax();
    }

    public function getShippingExcludeTax($total) {
        return $total->getAddress()->getShippingAmount();
    }

    public function setTotal($total, &$data) {
        //hai ta 2082014
        if ($this->displayBothTaxSub()) {
            $data['subtotal_excl_tax'] = $total['subtotal']->getValueExclTax();
            $data['subtotal_incl_tax'] = $total['subtotal']->getValueInclTax();
        } else {
            $data['subtotal'] = $total['subtotal']->getValue();
        }
	
        if ($this->includeTaxGrand($total['grand_total'])
                && $this->getTotalExclTaxGrand($total['grand_total'])) {
            $data['grand_total_excl_tax'] = $this->getTotalExclTaxGrand($total['grand_total']);
            $data['grand_total_incl_tax'] = $total['grand_total']->getValue();
        } else {
            $data['grand_total'] = $total['grand_total']->getValue();
        }

        if (isset($total['tax']) && $total['tax']->getValue() != 0) {
            $data['tax'] = $total['tax']->getValue();
        }

        if (isset($total['discount']) && $total['discount']) {
            $data['discount'] = abs($total['discount']->getValue());
        }
		 //coupon code (maybe)
		$coupon = '';
		if (Mage::getSingleton('checkout/session')->getQuote()->getCouponCode()){
			$coupon = Mage::getSingleton('checkout/session')->getQuote()->getCouponCode();
			$data['coupon_code'] = $coupon;
		}				
				
        if (isset($total['shipping'])) {
            if ($this->displayBothTaxShipping()) {
                if (!Mage::helper('connector')->isZero($this->getShippingIncludeTax($total['shipping']))) {
                    $data['shipping_hand_incl_tax'] = $this->getShippingIncludeTax($total['shipping']);
                }

                if (!Mage::helper('connector')->isZero($this->getShippingExcludeTax($total['shipping']))) {
                    $data['shipping_hand_excl_tax'] = $this->getShippingExcludeTax($total['shipping']);
                }
            } elseif ($this->displayIncludeTaxShipping()) {
                if (!Mage::helper('connector')->isZero($this->getShippingIncludeTax($total['shipping']))) {
                    $data['shipping_hand'] = $this->getShippingIncludeTax($total['shipping']);
                }
            } else {
                if (!Mage::helper('connector')->isZero($this->getShippingExcludeTax($total['shipping']))) {
                    $data['shipping_hand'] = $this->getShippingExcludeTax($total['shipping']);
                }
            }
        }
    }

}