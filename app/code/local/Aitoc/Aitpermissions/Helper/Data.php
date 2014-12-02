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

class Aitoc_Aitpermissions_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isShowingAllProducts()
    {
        return Mage::getStoreConfig('admin/general/showallproducts');
    }

    public function isShowingAllCustomers()
    {
        return Mage::getStoreConfig('admin/general/showallcustomers');
    }

    public function isShowProductOwner()
    {
        return Mage::getStoreConfig('admin/general/show_admin_on_product_grid');
    }

    public function isAllowedDeletePerWebsite()
    {
        return Mage::getStoreConfig('admin/general/allowdelete_perwebsite');
    }

    public function isAllowedDeletePerStoreview()
    {
        return Mage::getStoreConfig('admin/general/allowdelete');
    }

    public function isShowingProductsWithoutCategories()
    {
        return Mage::getStoreConfig('admin/general/allow_null_category');
    }

    /**
     * backward compatibility with Shopping Assistant
     */
    public function getAllowedCategories()
    {
        return Mage::getSingleton('aitpermissions/role')->getAllowedCategoryIds();
    }
    
    public function isQuickCreate()
    {
        return Mage::app()->getRequest()->getActionName() == 'quickCreate' ? true : false;
    }
}