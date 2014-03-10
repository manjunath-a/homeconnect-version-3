<?php
 
class Megnor_Manufacturer_Block_Navigation extends Mage_Catalog_Block_Navigation
{
 
	public function _toHtml() {
        if (!(bool) Mage::getStoreConfig('manufacturer/general/enable')) {
            return '';
        }
        return parent::_toHtml();
    }
	
	public function getBlockTitle()
    {
        return Mage::getStoreConfig('manufacturer/sidebar/blocktitle');
    }
	
	public function getNumberOfBrands()
    {
        return Mage::getStoreConfig('manufacturer/sidebar/number_of_brands');
    }
	
	public function getComboDropDown()
    {
        return Mage::getStoreConfig('manufacturer/sidebar/combodropdown');
    }  
	
	public function getManufacturerName($product)
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