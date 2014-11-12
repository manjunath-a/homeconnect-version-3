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
class Aitoc_Aitpermissions_Block_Rewrite_AdminPromoCatalogEdit extends Mage_Adminhtml_Block_Promo_Catalog_Edit
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $blockModel = Mage::registry('current_promo_catalog_rule');
            if ($blockModel->getWebsiteIds() && is_array($blockModel->getWebsiteIds()))
            {
                foreach ($blockModel->getWebsiteIds() as $blockWebsiteId)
                {
                    if (!in_array($blockWebsiteId, $role->getAllowedWebsiteIds()))
                    {
                        $this->_removeButton('delete');
                        $this->_removeButton('save');
                        $this->_removeButton('save_apply');
                        $this->_removeButton('save_and_continue_edit');
                        $this->_removeButton('save_and_continue');
                        
                        Mage::getSingleton('adminhtml/session')->addError(
                            Mage::helper('aitpermissions')->__(
                                'Sorry, you can not edit this rule because it is activated for the websites you do not have permissions for.'
                            ));
                        break;
                    }
                }
            }
        }

        return $this;
    }
}