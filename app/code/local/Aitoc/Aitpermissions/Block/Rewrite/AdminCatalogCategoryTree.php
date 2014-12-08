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

class Aitoc_Aitpermissions_Block_Rewrite_AdminCatalogCategoryTree
    extends Mage_Adminhtml_Block_Catalog_Category_Tree
{
    public function getCategoryCollection()
    {
        $collection = parent::getCategoryCollection();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $allowedCategoryIds = array();

            foreach ($role->getAllowedCategoryIds() as $allowedCategoryId)
            {
                $category = Mage::getModel('catalog/category')->load($allowedCategoryId);
                $categoryPath = $category->getPath();
                $categoryPathIds = explode('/', $categoryPath);

                $allowedCategoryIds = array_merge($allowedCategoryIds, $categoryPathIds);
            }

            if (!empty($allowedCategoryIds))
            {
                $collection->addIdFilter($allowedCategoryIds);
                $this->setData('category_collection', $collection);
            }
        }

        return $collection;
    }

    public function getMoveUrlPattern()
    {
        return $this->getUrl('*/catalog_category/move', array('store' => ':store'));
    }
}