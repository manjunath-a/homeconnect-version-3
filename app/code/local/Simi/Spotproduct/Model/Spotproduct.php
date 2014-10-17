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
 * @package 	Simi_Spotproduct
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Spotproduct Model
 * 
 * @category 	Simi
 * @package 	Simi_Spotproduct
 * @author  	Simi Developer
 */
class Simi_Spotproduct_Model_Spotproduct extends Simi_Connector_Model_Abstract {
	
	public $_limit = 10;
    public $_width = 600;
	public $_height = 600;
		
    public function _construct() {
        parent::_construct();
        $this->_init('spotproduct/spotproduct');
    }
	
	public function changeData($data_change, $event_name, $event_value) {
        $this->_data = $data_change;
        // dispatchEvent to change data
        $this->eventChangeData($event_name, $event_value);
        return $this->getCacheData();
    }

    public function setCacheData($data, $module_name = '') {
        if ($module_name == "simi_connector") {
            $this->_data = $data;
            return;
        }
        // if ($module_name == '' || is_null(Mage::getModel('connector/plugin')->checkPlugin($module_name)))
            // return;
        $this->_data = $data;
    }

    public function getCacheData() {
        return $this->_data;
    }
	
    public function getImageProduct($product, $file = null) {			
        if (!is_null($this->_width) && !is_null($this->_height)) {
            if ($file) {
                return Mage::helper('catalog/image')->init($product, 'thumbnail', $file)->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($this->_width, $this->_height)->__toString();
            }
            return Mage::helper('catalog/image')->init($product, 'small_image')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($this->_width, $this->_height)->__toString();
        }		
        if ($file) {
            return Mage::helper('catalog/image')->init($product, 'thumbnail', $file)->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(600, 600)->__toString();
        }
        return Mage::helper('catalog/image')->init($product, 'small_image')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(600, 600)->__toString();
    }
	
	public function getSpotProduct($data) {
        if (Mage::getStoreConfig('spotproduct/general/enable') == 0) {
            $information = $this->statusError(array('Extesnion was disabled'));
            return $information;
        }
        $this->_limit = $data->limit;
        $this->_width = $data->width;
        $this->_height = $data->height;
        $style_show = Mage::getStoreConfig('spotproduct/general/spot_product_value');
        $information = '';
        $productCollection = null;
        if ($style_show == 1) {
            $productCollection = $this->getBetterProduct();
        } elseif ($style_show == 2) {
            $productCollection = $this->getMostviewProduct();
        } elseif ($style_show == 3) {
            $productCollection = $this->getNewupdateProduct();
        } elseif ($style_show == 4) {
            $productCollection = $this->getRecentlyAddProduct();
        }

        if ($productCollection && $productCollection->getSize()) {

            $productCollection->addUrlRewrite(0);
            $productCollection->setPageSize($limit);
            $check_limit = 0;
            $productList = array();
            foreach ($productCollection as $product) {
				$_currentProduct = Mage::getModel('catalog/product')->load($product->getId());
                if (++$check_limit > $limit)
                    break;
                $productList[] = array(
                    'product_id' => $_currentProduct->getId(),
                    'product_name' => $_currentProduct->getName(),
                    'product_price' => $_currentProduct->getFinalPrice(),
                    'product_image' => $this->getImageProduct($_currentProduct, null),
                );
            }

            $information = $this->statusSuccess();
            $information['data'] = $productList;
            $information['message'] = array(Mage::getStoreConfig('spotproduct/general/spot_product'));
        }else {
            $information = $this->statusError();
        }
        return $information;
    }
	
    public function getSpotProducts($data) {
        if (Mage::getStoreConfig('spotproduct/general/enable') == 0) {
            $information = $this->statusError(array('Extesnion was disabled'));
            return $information;
        }
        $this->_limit = $data->limit;
        $this->_width = $data->width;
        $this->_height = $data->height;
		$style = $data->style;		
		$information = $this->statusSuccess();
		$productlist = array();
		if (!isset($style) || !$style || $style == "none"){
			$style_best = Mage::getStoreConfig('spotproduct/general/best_seller');
			$style_most = Mage::getStoreConfig('spotproduct/general/most_view');
			$style_new = Mage::getStoreConfig('spotproduct/general/new_update');
			$style_recent = Mage::getStoreConfig('spotproduct/general/recent_add');
			
			if ($style_best == 1){
				$productCollection = $this->getBetterProduct();
				$title  = Mage::helper("core")->__("Best Seller");		
				$key = "best_seller";
				$products = $this->getProductList($productCollection, $title, $key);
				$productlist[] = $products;
			}
			
			if ($style_most == 1){
				$productCollection = $this->getMostviewProduct();
				$title  = Mage::helper("core")->__("Most View");			
				$key = "most_view";
				$products = $this->getProductList($productCollection, $title, $key);
				$productlist[] = $products;
			}
			
			if ($style_new == 1){
				$productCollection = $this->getNewupdateProduct();
				$title  = Mage::helper("core")->__("Newly Updated");			
				$key = "new_update";
				$products = $this->getProductList($productCollection, $title, $key);
				$productlist[] = $products;
			}
			
			if ($style_recent == 1){
				$productCollection = $this->getRecentlyAddProduct();
				$title  = Mage::helper("core")->__("Recently Added");			
				$key = "recent_add";
				$products = $this->getProductList($productCollection, $title, $key);
				$productlist[] = $products;
			}
			$information["data"] = $productlist;			
		}else{
			$path = "spotproduct/general/".$style;
			$style_best = Mage::getStoreConfig($path);			
			if ($style_best == 1){
				$productCollection = null;				
				if ($style == "recent_add"){
					$productCollection = $this->getRecentlyAddProduct();					
				}elseif($style == "most_view"){
					$productCollection = $this->getMostviewProduct();
				}elseif($style == "new_update"){
					$productCollection = $this->getNewupdateProduct();
				}elseif($style == "best_seller"){
					$productCollection = $this->getBetterProduct();
				}
								
				$title  = Mage::helper("core")->__("Best Seller");			
				$key = $style;
				$products = $this->getProductList($productCollection, $title, $key);
				$productlist[] = $products;
			}
			$information["data"] = $productlist;			
		}		
		return $information;
        
    }
	
	public function getProductList($productCollection, $title, $key=""){
		
		$information = array();		
		$productList = array();
		if ($productCollection && $productCollection->getSize()) {			
            $productCollection->addUrlRewrite(0);
            $productCollection->setPageSize($limit);
            $check_limit = 0;            
            foreach ($productCollection as $product) {
				$_currentProduct = Mage::getModel('catalog/product')->load($product->getId());
                if (++$check_limit > $this->_limit)
                    break;
                $info_product = array(
                    'product_id' => $_currentProduct->getId(),
                    'product_name' => $_currentProduct->getName(),
					'product_regular_price' => Mage::app()->getStore()->convertPrice($product->getPrice(), false),
					'product_price' => Mage::app()->getStore()->convertPrice($product->getFinalPrice(), false),
                    'product_image' => $this->getImageProduct($_currentProduct, null),
                );
				 $event_name = $this->getControllerName() . '_product_detail';
				$event_value = array(
					'object' => $this,
					'product' => $_currentProduct
				);
				$data_change = $this->changeData($info_product, $event_name, $event_value);
				$productList[] = $data_change;
            }            
           
        }
		$information['data'] = $productList;
        $information['title'] = $title;
		$information['key'] = $key;
		return $information;
	}
   
	public function getBetterProduct() {
        $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addOrderedQty()
                ->addMinimalPrice()
                ->addTaxPercents()
                ->addStoreFilter()
                ->setOrder('ordered_qty', 'desc');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($_productCollection);
        return $_productCollection;
    }

    public function getMostviewProduct() {
        $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addViewsCount()
                ->addMinimalPrice()
                ->addTaxPercents()
                ->addStoreFilter();
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($_productCollection);
        return $_productCollection;
    }

    public function getNewupdateProduct() {
        $_productCollection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->setOrder('update_at', 'desc');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($_productCollection);
        return $_productCollection;
    }

    public function getRecentlyAddProduct() {
        $_productCollection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->setOrder('created_at', 'desc');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($_productCollection);
        return $_productCollection;
    }

}