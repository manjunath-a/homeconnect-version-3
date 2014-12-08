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

class Aitoc_Aitpermissions_Block_Rewrite_AdminCustomerGrid extends Mage_Adminhtml_Block_Customer_Grid
{
	protected function _prepareColumns()
	{
		parent::_prepareColumns();

        $role = Mage::getSingleton('aitpermissions/role');

		if ($role->isPermissionsEnabled())
		{
            if (!Mage::helper('aitpermissions')->isShowingAllCustomers() &&
                isset($this->_columns['website_id']))
            {
                unset($this->_columns['website_id']);
                $allowedWebsiteIds = $role->getAllowedWebsiteIds();

                if (count($allowedWebsiteIds) > 1)
                {
                    $websiteFilter = array();
                    foreach ($allowedWebsiteIds as $allowedWebsiteId)
                    {
                        $website = Mage::getModel('core/website')->load($allowedWebsiteId);
                        $websiteFilter[$allowedWebsiteId] = $website->getData('name');
                    }

                    $this->addColumn('website_id', array(
                        'header' => Mage::helper('customer')->__('Website'),
                        'align' => 'center',
                        'width' => '80px',
                        'type' => 'options',
                        'options' => $websiteFilter,
                        'index' => 'website_id',
                    ));
                }
            }
		}
        
        return $this;
	}
}