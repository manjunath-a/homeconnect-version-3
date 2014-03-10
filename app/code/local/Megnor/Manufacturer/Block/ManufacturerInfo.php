<?php
/**
* @copyright  Copyright (c) 2009 AITOC, Inc. 
*/
class Megnor_Manufacturer_Block_ManufacturerInfo extends Mage_Core_Block_Template
{
    protected $_manufacturer = null;
    
    public function __construct()
    {
        if (!$this->_manufacturer)
            $this->_manufacturer = Mage::getModel('manufacturer/manufacturer')->load($this->getRequest()->getParam('id'));
            
        $processor = Mage::getModel('core/email_template_filter');
        $html = $processor->filter(nl2br($this->_manufacturer->getContent()));
        $this->_manufacturer->setContent($html);
    }

    protected function _prepareLayout()
    {			  
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'),'title'=>Mage::helper('cms')->__('Go to Home Page'),'link'=>Mage::getBaseUrl()));
        $breadcrumbs->addCrumb('manufacturers',array('label'=>Mage::helper('manufacturer')->__('Manufacturers'),'title'=>Mage::helper('manufacturer')->__('Go to All Brands List'),'link'=>Mage::helper('manufacturer')->getManufacturersUrl()));
        $breadcrumbs->addCrumb('manufacturer',array('label'=>$this->_manufacturer->getTitle(),'title'=>$this->_manufacturer->getTitle()));

        
		if ($root = $this->getLayout()->getBlock('root')) {
            $template = (string)Mage::getConfig()->getNode('global/manufacturer/layouts/'.$this->_manufacturer->getRootTemplate().'/template');
            $root->setTemplate($template);
            $root->addBodyClass('manufacturer-'.$this->_manufacturer->getUrlKey());
        }

        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setTitle($this->_manufacturer->getTitle());
            if ($this->_manufacturer->getMetaKeywords())
                $head->setKeywords($this->_manufacturer->getMetaKeywords());
            if ($this->_manufacturer->getMetaDescription())
            $head->setDescription($this->_manufacturer->getMetaDescription());
        }
		
	
    }
    
    public function getManufacturerName()
    {
        return $this->_manufacturer;
    }
	
	public function _toHtml() {
        if (!(bool) Mage::getStoreConfig('manufacturer/general/enable')) {
            return '';
        }
        return parent::_toHtml();
    }
}
