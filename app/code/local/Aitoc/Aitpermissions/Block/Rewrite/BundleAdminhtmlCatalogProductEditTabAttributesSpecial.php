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

class Aitoc_Aitpermissions_Block_Rewrite_BundleAdminhtmlCatalogProductEditTabAttributesSpecial
    extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes_Special
{
    public function checkFieldDisable()
    {        
        $result = parent::checkFieldDisable();

        if ($this->getElement() && 
            $this->getElement()->getEntityAttribute() &&
            $this->getElement()->getEntityAttribute()->isScopeGlobal())
        {
            if (!Mage::getSingleton('aitpermissions/role')->canEditGlobalAttributes())
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