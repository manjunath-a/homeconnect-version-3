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
 * @package 	Magestore_Bannerslider
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

 /**
 * Bannerslider Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Simi_Connector_Model_Value extends Mage_Core_Model_Abstract
{
	public function _construct(){
		parent::_construct();
        $this->_init('connector/value');
    }

     public function loadAttributeValue($bannerId, $websiteId, $attributeCode ) {
        $attributeValue = $this->getCollection()
                ->addFieldToFilter('banner_id', $bannerId)
                ->addFieldToFilter('website_id', $websiteId)
                ->addFieldToFilter('attribute_code', $attributeCode)
                ->getFirstItem();
        $this->setData('banner_id', $bannerId)
                ->setData('website_id', $websiteId)
                ->setData('attribute_code', $attributeCode);
        if ($attributeValue)
            $this->addData($attributeValue->getData())
                    ->setId($attributeValue->getId());
        return $this;
    }

}