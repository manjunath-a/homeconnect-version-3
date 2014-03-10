<?php

class Megnor_Manufacturer_Model_Manufacturer extends Mage_Core_Model_Abstract
{
    protected $_collection = null;
    protected $_optionCollection = null;
    protected static $_url = null;

    public function _construct()
    {
        parent::_construct();
        $this->_init('manufacturer/manufacturer');
    }
	public function loadByManufacturerId($manufacturerId)
    {
        $storeId = Mage::app()->getStore()->getId();
        return $this->getCollection()
            ->addFieldToFilter('manufacturer_name', array("eq"=>$manufacturerId))->getFirstItem();
    }
    
	public function getUrl()
    {
        if ($this->getId())
        {
            $storeId = Mage::app()->getStore()->getId();
            $rewriteModel = Mage::getModel('core/url_rewrite');
            $rewriteCollection = $rewriteModel->getCollection();
            $rewriteCollection->addStoreFilter($storeId, true)
                              ->setOrder('store_id', 'DESC')
                              ->addFieldToFilter('target_path', 'manufacturer/index/view/id/' . $this->getId())
                              ->setPageSize(1)
                              ->load();
           if (count($rewriteCollection) > 0)
           {
                foreach ($rewriteCollection as $rewrite) {
                    $rewriteModel->setData($rewrite->getData());
                }
                /**
                * Checking if we need to add store code to url
                */
                $sStoreCode = '';
//                
//                if (Mage::getStoreConfig('web/url/use_store'))
//                {
//                    $sStoreCode = Mage::app()->getStore()->getCode() . '/';
//                }
                
                return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . $sStoreCode . $rewriteModel->getRequestPath();
           } else 
           {
               return $this->getUrlInstance()->getUrl('manufacturer/index/view', array('id' => $this->getId()));
           }
        }
        return '';
//        if ($this->getId())
//            return $this->getUrlInstance()->getUrl(Mage::helper('aitmanufacturers')->getUrlPrefix().'/'.$this->getUrlKey());
    }
	
	
	 public function getUrlInstance()
    {
        if (!self::$_url) {
            self::$_url = Mage::getModel('core/url');
        }
        return self::$_url;
    }
	
	  public function getProductsByManufacturer($manufacturerId, $storeId)
    {
        $resource = Mage::getResourceModel('catalogindex/attribute');
        $select = $resource->getReadConnection()->select();
      
	  
	    
        $select->from($resource->getMainTable(), 'entity_id')
            ->distinct(true)
            ->where('store_id = ?', $storeId)
            ->where('attribute_id = ?', Mage::helper('manufacturer')->getAttributeId())
            ->where('value = ?', $manufacturerId);
        return $resource->getReadConnection()->fetchCol($select);
    }
    
    public function getManufacturersByProducts($productIds, $storeId)
    {
        $resource = Mage::getResourceModel('catalogindex/attribute');
        $select = $resource->getReadConnection()->select();

        if (empty($productIds))
        {
            return array();
        }
        
        $select->from($resource->getMainTable(), 'value')
            ->distinct(true)
            ->where('store_id = ?', $storeId)
            ->where('attribute_id = ?', Mage::helper('manufacturer')->getAttributeId())
            ->where('entity_id IN (?)', $productIds);

        return $resource->getReadConnection()->fetchCol($select);
    }
	
	
}