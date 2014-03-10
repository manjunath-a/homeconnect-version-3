<?php

class Megnor_Manufacturer_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_CONFIG_URL_PREFIX = 'manufacturer/general/url_prefix';
    const XML_CONFIG_ATTRIBUTE_CODE = 'manufacturer/general/attribute_code';    
    const XML_CONFIG_SHOW_LOGO = 'manufacturer/general/show_logo';
    const XML_CONFIG_SHOW_LINK = 'manufacturer/general/show_link';
	const XML_CONFIG_BRIEF_NUM = 'manufacturer/manufacturer/number_of_brands';
	 
	
	public function getIsEnable() {
        return (bool) Mage::getStoreConfig('manufacturer/general/enable');
    }
    
    public function toUrlKey($string)
    {
        $urlKey = preg_replace(array('/[^a-z0-9-_]/i', '/[ ]{2,}/', '/[ ]/'), array(' ', ' ', '-'), $string);
        if (empty($urlKey)){
            $urlKey = time();
        }
        return strtolower($urlKey);
    }
    
    public function getManufacturersUrl()
    {
        return Mage::getModel('core/url')->getUrl(Mage::getStoreConfig(self::XML_CONFIG_URL_PREFIX));
    }
    
    public function getUrlPrefix()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_URL_PREFIX);
    }
    
    public function getAttributeCode($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_CONFIG_ATTRIBUTE_CODE, $storeId);
    }
    
    public function getAttributeId($storeId = null)
    {
        return Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() )
//          ->addVisibleFilter()->setCodeFilter($this->getAttributeCode($storeId))
            ->setCodeFilter($this->getAttributeCode($storeId))
            ->getFirstItem()
            ->getAttributeId();
    }    
    
    public function getColumnsNum()
    {
        $num = (int)Mage::getStoreConfig(self::XML_CONFIG_COLUMNS_NUM);
        return $num > 0 ? $num : 2;
    }
    
    public function getBriefNum()
    {
        $num = (int)Mage::getStoreConfig(self::XML_CONFIG_BRIEF_NUM);
        return $num > 0 ? $num : 9999;
    }
    
    public function getShowLogo()
    {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_SHOW_LOGO);
    }
    
    public function getShowLink()
    {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_SHOW_LINK);
    }
    

    public function getManufacturerLink($product)
    {

	    $manufacturerId = $product->getData($this->getAttributeCode());
		$manufacturer = Mage::getModel('manufacturer/manufacturer')->loadByManufacturerId($manufacturerId);
        if ($manufacturer->getManufacturerId()){
            $html = '';
            $logo = Mage::getBaseUrl('media')."manufacturer"."/"."resized"."/".$manufacturer->getFilename();
			
            if ($logo && $this->getShowLogo()){
                $html .= '<a href="'.$manufacturer->getUrl().'"><img src="'.$logo.'" alt="'.$manufacturer->getTitle().'" title="'.$manufacturer->getTitle().'" /></a><br />'; 
            }
            if ($this->getShowLink()){                
				$html .= '<a href="'.$manufacturer->getUrl().'">'.$this->__('See other products by %s', $manufacturer->getTitle()). '</a>';
            }	
				
            return $html;
        }
    }
	
	 
	
}