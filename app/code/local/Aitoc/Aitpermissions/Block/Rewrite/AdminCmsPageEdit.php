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

class Aitoc_Aitpermissions_Block_Rewrite_AdminCmsPageEdit extends Mage_Adminhtml_Block_Cms_Page_Edit
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $page = Mage::registry('cms_page');
            
            // if page is assigned to store views of allowed website only, will allow to delete it
            if ($page->getStoreId() && is_array($page->getStoreId()))
            {
                foreach ($page->getStoreId() as $storeId)
                {
                    if (!in_array($storeId, $role->getAllowedStoreviewIds()))
                    {
                        $this->_removeButton('delete');
                        break 1;
                    }
                }
            }
        }
        
        return $this;
    }
}