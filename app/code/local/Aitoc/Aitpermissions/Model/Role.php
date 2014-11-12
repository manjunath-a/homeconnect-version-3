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
 * @author yahnenko
 */

/*
 * Use as singleton
 * Mage::getSingleton('aitpermissions/role')
 */

/**
 * @refactor
 * make 3 subclasses - Strategy pattern
 * Aitoc_Aitpermissions_Model_Role_StoreLimited
 * Aitoc_Aitpermissions_Model_Role_WebsiteLimited
 * Aitoc_Aitpermissions_Model_Role_NotLimited - Null Object pattern
 * exclude all checks [if ($this->isScopeStore())] and [if ($this->isScopeWebsite())]
 */

class Aitoc_Aitpermissions_Model_Role
{
    const SCOPE_WEBSITE = 'website';
    const SCOPE_STORE   = 'store';

    private $_helper = null;

    private $_recordCollection = null;

    private $_permissionsEnabled = null;
    private $_scope = null;
    private $_canEditGlobalAttributes = null;
    private $_canEditOwnProductsOnly = null;

    private $_websiteIds = null;
    private $_storeIds = null;
    private $_storeviewIds = null;
    private $_categoryIds = null;

    private $_allowedWebsiteIds = null;
    private $_allowedStoreIds = null;
    private $_allowedStoreviewIds = null;
    private $_allowedCategoryIds = null;

    public function __construct()
    {
        $this->_helper = Mage::helper('aitpermissions');
    }

    private function _getCurrentRoleId()
    {
        if (!Mage::app()->getStore()->isAdmin())
        {
            return 0;
        }

        $session = Mage::getSingleton('admin/session');

        if ($user = $session->getUser())
        {
            return $user->getRole()->getId();
        }

        return 0;
    }

    private function _getRecordCollection()
    {
        if (null == $this->_recordCollection)
        {
            $this->_recordCollection = Mage::getModel('aitpermissions/advancedrole')
                ->getCollection()
                ->loadByRoleId($this->_getCurrentRoleId());
        }

        return $this->_recordCollection;
    }

    public function isPermissionsEnabled()
    {
        if (null == $this->_permissionsEnabled)
        {
            $this->_permissionsEnabled = (bool)$this->_getRecordCollection()->getSize();
        }

        return $this->_permissionsEnabled;
    }

    // scope

    public function getScope()
    {
        if (null == $this->_scope)
        {
            $this->_scope = $this->getStoreviewIds() ?
                self::SCOPE_STORE :
                self::SCOPE_WEBSITE;
        }

        return $this->_scope;
    }

    public function isScopeStore()
    {
        return $this->isPermissionsEnabled() &&
            self::SCOPE_STORE == $this->getScope();
    }

    public function isScopeWebsite()
    {
        return $this->isPermissionsEnabled() &&
            self::SCOPE_WEBSITE == $this->getScope();
    }

    // stored values

    public function getWebsiteIds()
    {
        if (null == $this->_websiteIds)
        {
            $this->_websiteIds = array();

            foreach ($this->_getRecordCollection() as $record)
            {
                if ($record->getWebsiteId())
                {
                    $this->_websiteIds[] = $record->getWebsiteId();
                }
            }
        }

        return $this->_websiteIds;
    }

    public function getStoreIds()
    {
        if (null == $this->_storeIds)
        {
            $this->_storeIds = array();

            foreach ($this->_getRecordCollection() as $record)
            {
                $this->_storeIds[] = $record->getStoreId();
            }
        }

        return $this->_storeIds;
    }

    public function getStoreviewIds()
    {
        if (null == $this->_storeviewIds)
        {
            $this->_storeviewIds = array();

            foreach ($this->_getRecordCollection() as $record)
            {
                $this->_storeviewIds = array_merge($this->_storeviewIds, $record->getStoreviewIdsArray());
            }
        }

        return $this->_storeviewIds;
    }

    public function getCategoryIds()
    {
        if (null == $this->_categoryIds)
        {
            $this->_categoryIds = array();

            foreach ($this->_getRecordCollection() as $record)
            {
                $this->_categoryIds = array_unique(array_merge($this->_categoryIds, $record->getCategoryIdsArray()));
            }
        }

        return $this->_categoryIds;
    }

    // calculated allowed values

    public function getAllowedWebsiteIds()
    {
        if (null == $this->_allowedWebsiteIds)
        {
            $this->_allowedWebsiteIds = array();

            if ($this->isScopeStore())
        	{
            	$storeCollection = Mage::getModel('core/store_group')
                    ->getCollection()
                    ->addFieldToFilter('group_id', array('in' => $this->getStoreIds()))
                    ->load();
                
                foreach ($storeCollection as $store)
                {
                    $this->_allowedWebsiteIds[] = $store->getWebsiteId();
                }

                $this->_allowedWebsiteIds = array_unique($this->_allowedWebsiteIds);
        	}

        	if ($this->isScopeWebsite())
        	{
                $this->_allowedWebsiteIds = $this->getWebsiteIds();
        	}
        }

        return $this->_allowedWebsiteIds;
    }

    public function getAllowedStoreIds()
    {
        if (null == $this->_allowedStoreIds)
        {
            if ($this->isScopeStore())
        	{
        	    $this->_allowedStoreIds = $this->getStoreIds();
        	}

        	if ($this->isScopeWebsite())
        	{
                $this->_allowedStoreIds = array();
                $storeCollection = Mage::getModel('core/store_group')
                    ->getCollection()
                    ->addWebsiteFilter($this->getWebsiteIds());
                foreach ($storeCollection as $store)
                {
                    $this->_allowedStoreIds[] = $store->getGroupId();
                }
        	}
        }
        return $this->_allowedStoreIds;
    }

    public function getAllowedStoreviewIds()
    {
        if (null == $this->_allowedStoreviewIds)
        {
            if ($this->isScopeStore())
        	{
        	    $this->_allowedStoreviewIds = $this->getStoreviewIds();
        	}

        	if ($this->isScopeWebsite())
        	{
                $this->_allowedStoreviewIds = array();
                $storeviewCollection = Mage::getModel('core/store')
                    ->getCollection()
                    ->addWebsiteFilter($this->getWebsiteIds());
                
                foreach ($storeviewCollection as $Storeview)
                {
                    $this->_allowedStoreviewIds[] = $Storeview->getStoreId();
                }
        	}
        }
        
        return $this->_allowedStoreviewIds;
    }

    public function getAllowedCategoryIds()
    {
        if (null === $this->_allowedCategoryIds)
        {
            $this->_allowedCategoryIds = array();

            if ($this->isScopeStore())
            {
                $this->_allowedCategoryIds = $this->getCategoryIds();
            }
            
            if ($this->isScopeWebsite())
            {
                foreach ($this->_getRecordCollection() as $record)
                {
                    $this->_allowedCategoryIds = array_merge(
                        $this->_allowedCategoryIds,
                        $this->_getWebsiteCategoryIds($record->getWebsiteId())
                    );
                }
            }
        }

        return $this->_allowedCategoryIds;
    }

    // action permissions

    public function isAllowedToDelete()
    {
        return ($this->isScopeStore() && $this->_helper->isAllowedDeletePerStoreview()) ||
            ($this->isScopeWebsite() && $this->_helper->isAllowedDeletePerWebsite());
    }

    public function canEditGlobalAttributes()
    {
        if (null == $this->_canEditGlobalAttributes)
        {
            $this->_canEditGlobalAttributes = (bool)$this->_getRecordCollection()
                ->getFirstItem()
                ->getCanEditGlobalAttr();
        }

        return $this->_canEditGlobalAttributes;
    }

    public function canEditOwnProductsOnly()
    {
        if (null == $this->_canEditOwnProductsOnly)
        {
            $this->_canEditOwnProductsOnly = (bool)$this->_getRecordCollection()
                ->getFirstItem()
                ->getCanEditOwnProductsOnly();
        }

        return $this->_canEditOwnProductsOnly;
    }

    // product permissions

    public function isOwnProduct($product)
    {
        $productOwnerId = $product->getCreatedBy();
        $adminId = Mage::getSingleton('admin/session')->getUser()->getUserId();

        if ($productOwnerId && $adminId)
        {
            return $productOwnerId == $adminId;
        }

        return true;
    }

    public function isAllowedToEditProduct($product)
    {
        if ($this->isScopeStore())
        {
            if(sizeof($product->getCategoryIds())==0 && Mage::helper('aitpermissions')->isShowingProductsWithoutCategories() == 1) {
                return true;
            }
            return (bool)array_intersect($this->getAllowedCategoryIds(), $product->getCategoryIds());
        }

        if ($this->isScopeWebsite())
        {
            return (bool)array_intersect($this->getAllowedWebsiteIds(), $product->getWebsiteIds());
        }

        return false;
    }

    /**
     *
     * @param Mage_Catalog_Model_Category $category
     * @return boolean
     */
    public function isAllowedToEditCategory($category)
    {
        if($this->isPermissionsEnabled() && $category->getId() && !in_array($category->getId(), $this->getAllowedCategoryIds())) {
            return false;
        }
        return true;
    }

    public function addAllowedCategoryId($addCategoryIds, $storeId)
    {
        if(is_array($this->_allowedCategoryIds)) {
            $this->_allowedCategoryIds[] = $addCategoryIds;
        }

        if ($this->isScopeWebsite())
        {
            return;
        }

        foreach ($this->_getRecordCollection() as $record)
        {
            if ($record->getStoreId() == $storeId)
            {
                $categoryIds = explode(',', $record->getCategoryIds());
                $categoryIds = array_merge($categoryIds, (array)$addCategoryIds);
                $categoryIds = array_unique($categoryIds);

                $record->setCategoryIds(implode(',', $categoryIds));
                $record->save();
            }
        }
    }

    public function getCategoryIdsFromAllowedStores()
    {
        $categoryIds = array();

        foreach ($this->_getRecordCollection() as $record)
        {
            $categoryIds = array_merge(
                $categoryIds,
                $this->_getStoreCategoryIds($record->getStoreId())
            );
        }

        return $categoryIds;
    }

    private function _getStoreCategoryIds($storeId)
    {
        $groupCollection = Mage::getModel('core/store_group')
            ->getCollection()
            ->addFieldToFilter('main_table.group_id', array('in' => $storeId))
            ->load();

        return $this->_getStoreGroupCategoryIds($groupCollection);
    }

    private function _getWebsiteCategoryIds($websiteId)
    {
        $groupCollection = Mage::getModel('core/store_group')
            ->getCollection()
            ->addWebsiteFilter($websiteId)
            ->load();

        return $this->_getStoreGroupCategoryIds($groupCollection);
    }

    private function _getStoreGroupCategoryIds($groupCollection)
    {
        $rootCategoryIds = array();
        foreach ($groupCollection as $group)
        {
            $rootCategoryIds[] = $group->getRootCategoryId();
        }

        $categoryIds = array();

        foreach ($rootCategoryIds as $rootCaregoryId)
        {
            $rootCategory = Mage::getModel('catalog/category')->load($rootCaregoryId);

            $childCategories = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('entity_id')
                ->addAttributeToFilter('path', array('like' => $rootCategory->getPath() . '/%'))
                ->load();

            foreach ($childCategories as $childCategory)
            {
                $categoryIds[] = $childCategory->getId();
            }
        }

        return array_unique(array_merge($rootCategoryIds, $categoryIds));
    }
}