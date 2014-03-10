<?php
/**
* @copyright  Copyright (c) 2009 AITOC, Inc. 
*/
class Megnor_Manufacturer_Block_Product_List extends Mage_Catalog_Block_Product_List 
{
    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection;
    protected $_manufacturer;
  
    
    public function __construct(){
	
        $manufacturers = Mage::registry('manufacturer_data');
        if (isset($manufacturers[$this->getRequest()->getParam('id')])){
            $this->_manufacturer = $manufacturers[$this->getRequest()->getParam('id')];
        }
        else {
            $this->_manufacturer = Mage::getModel('manufacturer/manufacturer')->load($this->getRequest()->getParam('id'))
                ->getManufacturerName();
            $manufacturers[$this->getRequest()->getParam('id')] = $this->_manufacturer;
            Mage::register('manufacturer_data', $manufacturers);
        }
    }
    


    /**
     * Retrieve loaded product collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $collection = Mage::getResourceModel('catalog/product_collection');
            $attributes = Mage::getSingleton('catalog/config')
                ->getProductAttributes();
            
			$collection->addAttributeToSelect($attributes)
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addStoreFilter();

            $productIds = Mage::getModel('manufacturer/manufacturer')->getProductsByManufacturer($this->_manufacturer, Mage::app()->getStore()->getId());
			
			
            $collection->addAttributeToFilter(Mage::helper('manufacturer')->getAttributeCode(), array('eq' => $this->_manufacturer), 'left');
            $collection->addIdFilter($productIds);
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }
    
    
    
    protected function _toHtml()
    {
        if ($this->_getProductCollection()->count()){
            return parent::_toHtml();
        }
        return '';
    }
	
}
