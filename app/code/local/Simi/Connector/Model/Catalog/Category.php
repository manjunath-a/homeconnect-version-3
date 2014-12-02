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
 * @package 	Magestore_Madapter
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Madapter Model
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Catalog_Category extends Simi_Connector_Model_Catalog {

    public function getListCategory($categoryCollection, $device_id) {
		
		$webId = Mage::app()->getWebsite()->getId();		
		$categoriesDB = Mage::getModel('connector/app')->getCollection()
				->addFieldToFilter('website_id', array('eq' => $webId))
                ->addFieldToFilter('device_id', array('eq' => $device_id))->getFirstItem()->getCategories();		
		$categories = explode(",", $categoriesDB);		
		
        $cateList = array();
		if (count($categories) != 1 || $categoriesDB != null){
		
			foreach ($categoryCollection as $cate) {
				$_cate = Mage::getModel('catalog/category')->load($cate->getId());
				$_includeMenu = 1;
				if (version_compare(Mage::getVersion(), '1.4.0.1', '>') === true) {
					// $_includeMenu = (int) $_cate->getIncludeInMenu();
				}
				if ($_includeMenu && in_array($cate->getId(), $categories)) {
					if (!$cate->hasChildren()) {
						$cateList[] = array(
							'category_id' => $cate->getId(),
							'category_name' => $cate->getName() == null ? 'ROOT' : $cate->getName(),
							'category_image' => $_cate->getImageUrl() == false ? null : $_cate->getImageUrl(),
							'has_child' => 'NO',
						);
					} else {
						$cateList[] = array(
							'category_id' => $cate->getId(),
							'category_name' => $cate->getName() == null ? 'ROOT' : $cate->getName(),
							'category_image' => $_cate->getImageUrl() == false ? null : $_cate->getImageUrl(),
							'has_child' => 'YES',
						);
					}
				}
			}
		}else{
			foreach ($categoryCollection as $cate) {
				$_cate = Mage::getModel('catalog/category')->load($cate->getId());
				$_includeMenu = 1;
				if (version_compare(Mage::getVersion(), '1.4.0.1', '>') === true) {
					// $_includeMenu = (int) $_cate->getIncludeInMenu();
				}
				if ($_includeMenu) {
					if (!$cate->hasChildren()) {
						$cateList[] = array(
							'category_id' => $cate->getId(),
							'category_name' => $cate->getName() == null ? 'ROOT' : $cate->getName(),
							'category_image' => $_cate->getImageUrl() == false ? null : $_cate->getImageUrl(),
							'has_child' => 'NO',
						);
					} else {
						$cateList[] = array(
							'category_id' => $cate->getId(),
							'category_name' => $cate->getName() == null ? 'ROOT' : $cate->getName(),
							'category_image' => $_cate->getImageUrl() == false ? null : $_cate->getImageUrl(),
							'has_child' => 'YES',
						);
					}
				}
			}
		}
        
        $information = $this->statusSuccess();
        // $information['total'] = $product_total;
        $information['data'] = $cateList;
        return $information;
    }

    public function getCategories($data, $device_id) {
        $category_id = $data->category_id;
        $recursionLevel = max(0, (int) Mage::app()->getStore()->getConfig('catalog/navigation/max_depth'));
        $parent = Mage::app()->getStore()->getRootCategoryId();
        if ($category_id) {
            $parent = $category_id;
        }
        $category = Mage::getModel('catalog/category')->load($parent);
        //$child = $category->getResource()->getChildren($category, false);		        
        $categoryCollection = $category->getChildrenCategories();
        //Zend_debug::dump($categoryCollection);die();      
        return $this->getListCategory($categoryCollection, $device_id);
    }

}

?>
