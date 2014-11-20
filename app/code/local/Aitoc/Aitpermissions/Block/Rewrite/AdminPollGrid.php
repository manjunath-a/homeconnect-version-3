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

class Aitoc_Aitpermissions_Block_Rewrite_AdminPollGrid extends Mage_Adminhtml_Block_Poll_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('poll/poll')->getCollection();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $collection->addStoreFilter($role->getAllowedStoreviewIds());
        }

        $this->setCollection($collection);
        Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();

        if (!Mage::app()->isSingleStoreMode())
        {
            $this->getCollection()->addStoreData();
        }
        
        return $this;
    }
}