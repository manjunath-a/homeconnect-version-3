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

class Aitoc_Aitpermissions_Block_Rewrite_AdminhtmlCatalogFormRendererFieldsetElement
    extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
{
    public function checkFieldDisable()
    {        
        $result = parent::checkFieldDisable();
        $superGlobalAttribute = array('sku', 'weight');
        $currentProduct = Mage::registry('current_product');
        $bAllow = !$currentProduct || !$currentProduct->getId() || !$currentProduct->getSku();

        if ($bAllow && 
            $this->getElement() &&
            $this->getElement()->getEntityAttribute() &&
            in_array($this->getElement()->getEntityAttribute()->getAttributeCode(), $superGlobalAttribute))
        {
            return $result;
        }
        
        if ($this->getElement() && 
            $this->getElement()->getEntityAttribute() &&
            $this->getElement()->getEntityAttribute()->isScopeGlobal())
        {
            $role = Mage::getSingleton('aitpermissions/role');
            $superGlobalAttribute = array('created_by');

            if ($role->isPermissionsEnabled() && (!$role->canEditGlobalAttributes() || in_array($this->getElement()->getEntityAttribute()->getAttributeCode(), $superGlobalAttribute)))
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
    
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::render($element);
        if ($this->getElement() && 
            $this->getElement()->getEntityAttribute() &&
            $this->getElement()->getEntityAttribute()->isScopeGlobal())
        {
            $role = Mage::getSingleton('aitpermissions/role');

            if ($role->isPermissionsEnabled() &&
                !$role->canEditGlobalAttributes() &&
                ('msrp' == $this->getElement()->getHtmlId()))
            {
                 $html .= '
                    <script type="text/javascript">
                    //<![CDATA[
                    if (Prototype.Browser.IE)
                    {
                        if (window.addEventListener)
                        {
                            window.addEventListener("load", aitpermissions_disable_msrp, false);
                        }
                        else
                        {
                            window.attachEvent("onload", aitpermissions_disable_msrp);
                        }
                    }
                    else
                    {
                        document.observe("dom:loaded", aitpermissions_disable_msrp);
                    }

                    function aitpermissions_disable_msrp()
                    {
                        ["click", "focus", "change"].each(function(evt){
                            var msrp = $("msrp");
                            if (msrp && !msrp.disabled)
                            {
                                Event.observe(msrp, evt, function(el) {
                                    el.disabled = true;
                                }.curry(msrp));
                            }
                        });
                    }
                    //]]>
                    </script>';
            }
            
            if (!$role->canEditGlobalAttributes())
            {
                $html = str_replace(
                    '<script type="text/javascript">toggleValueElements(',
                    '<script type="text/javascript">//toggleValueElements(',
                    $html
                );
            }
        }
        
        return $html;
    }
}