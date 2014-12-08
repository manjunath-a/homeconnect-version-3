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

class Aitoc_Aitpermissions_Block_Rewrite_AdminhtmlCatalogFormRendererAttributeUrlkey
    extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Attribute_Urlkey
{
    public function getElementHtml()
    {
        $html = parent::getElementHtml();
        $element = $this->getElement();
        
        if ($element && $element->getEntityAttribute() &&
            $element->getEntityAttribute()->isScopeGlobal())
        {
            $role = Mage::getSingleton('aitpermissions/role');

            if ($role->isPermissionsEnabled() && !$role->canEditGlobalAttributes())
            {
                $html = str_replace('type="text"', ' disabled="disabled" type="text"', $html);
            }
        }

        return $html;
    }
}