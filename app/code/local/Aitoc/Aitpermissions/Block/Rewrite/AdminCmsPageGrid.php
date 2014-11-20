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
class Aitoc_Aitpermissions_Block_Rewrite_AdminCmsPageGrid extends Mage_Adminhtml_Block_Cms_Page_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cms/page')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Page_Collection */
        $collection->setFirstStoreFlag(true);

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $collection->getSelect()->join(
                array('store_table_permissions' => $collection->getTable('cms/page_store')),
                'main_table.page_id = store_table_permissions.page_id',
                array()
            )
            ->where('store_table_permissions.store_id in (?)', $role->getAllowedStoreviewIds())
            ->group('main_table.page_id');            
        }

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
}