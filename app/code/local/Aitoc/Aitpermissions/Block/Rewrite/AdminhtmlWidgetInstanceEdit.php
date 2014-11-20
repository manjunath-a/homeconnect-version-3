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
class Aitoc_Aitpermissions_Block_Rewrite_AdminhtmlWidgetInstanceEdit
    extends Mage_Widget_Block_Adminhtml_Widget_Instance_Edit
{
    protected function _preparelayout()
    {
        parent::_prepareLayout();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $widgetInstance = Mage::registry('current_widget_instance');

            // checking if we have permissions to edit this widget
            if ($widgetInstance->getId() &&
                is_array($widgetInstance->getStoreIds()) &&
                !array_intersect($widgetInstance->getStoreIds(), $role->getAllowedStoreviewIds()))
            {
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('*/*'));
            }

            if (!$widgetInstance->getStoreIds() ||
                array_diff($widgetInstance->getStoreIds(), $role->getAllowedStoreviewIds()))
            {
                $this->_removeButton('delete');
            }
        }
        
        return $this;
    }
}