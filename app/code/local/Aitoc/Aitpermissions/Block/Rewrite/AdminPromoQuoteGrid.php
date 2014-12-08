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
class Aitoc_Aitpermissions_Block_Rewrite_AdminPromoQuoteGrid extends Mage_Adminhtml_Block_Promo_Quote_Grid
{
    protected function _prepareCollection()
    {
        /** @var $collection Mage_SalesRule_Model_Mysql4_Rule_Collection */
        $collection = Mage::getModel('salesrule/rule')->getResourceCollection();
        if(version_compare(Mage::getVersion(), '1.7.0.0', '>='))
        {
            $collection->addWebsitesToResult();
        }
        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $collection->addWebsiteFilter($role->getAllowedWebsiteIds());
            $collection->setFlag('is_website_table_joined', false);
        }
        $this->setCollection($collection);

        Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            unset($this->_columns['rule_website']);
            $allowedWebsiteIds = $role->getAllowedWebsiteIds();

            if (count($allowedWebsiteIds) > 1)
            {
                $websiteFilter = array();
                foreach ($allowedWebsiteIds as $allowedWebsiteId)
                {
                    $website = Mage::getModel('core/website')->load($allowedWebsiteId);
                    $websiteFilter[$allowedWebsiteId] = $website->getData('name');
                }

                $this->addColumn('rule_website', array(
                    'header'    => Mage::helper('salesrule')->__('Website'),
                    'align'     =>'left',
                    'index'     => 'website_ids',
                    'type'      => 'options',
                    'sortable'  => false,
                    'options'   => $websiteFilter,
                    'width'     => 200
                ));
            }
        }

        return $this;
    }
}