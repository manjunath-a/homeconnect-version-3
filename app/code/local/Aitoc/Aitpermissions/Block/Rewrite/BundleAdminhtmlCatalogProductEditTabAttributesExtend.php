<?php
/**
 * Advanced Permissions
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitpermissions
 * @version      2.8.1
 * @license:     Kl7jRk0he17edeJ6OS19LXc2T80wKqLuOh4O30S6vG
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
/**
* @copyright  Copyright (c) 2012 AITOC, Inc.
*/

class Aitoc_Aitpermissions_Block_Rewrite_BundleAdminhtmlCatalogProductEditTabAttributesExtend
    extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Extend
{
    public function checkFieldDisable()
    {        
        $result = parent::checkFieldDisable();
		$superGlobalAttribute = array('sku','weight');
		$currentProduct = Mage::registry('current_product');
        $bAllow = !$currentProduct || !$currentProduct->getId() || !$currentProduct->getSku();
        
        if ($bAllow && 
            $this->getElement() &&
            $this->getElement()->getEntityAttribute()
            && in_array($this->getElement()->getEntityAttribute()->getAttributeCode(), $superGlobalAttribute))
        {
            return $result;
        }
		
        if ($this->getElement() && 
            $this->getElement()->getEntityAttribute() &&
            $this->getElement()->getEntityAttribute()->isScopeGlobal())
        {          
            $role = Mage::getSingleton('aitpermissions/role');

            if ($role->isPermissionsEnabled() && !$role->canEditGlobalAttributes())
            {                
                $this->getElement()->setDisabled(true);
                $this->getElement()->setReadonly(true);
                $afterHtml = $this->getElement()->getAfterElementHtml();
                if (false !== strpos($afterHtml, 'type="checkbox"'))
                {
                    $afterHtml = str_replace('type="checkbox"', 'type="checkbox" disabled="disabled"', $afterHtml);
                    $this->getElement()->setAfterElementHtml($afterHtml);
                }
            }            
        }
        
        return $result;
    }
}