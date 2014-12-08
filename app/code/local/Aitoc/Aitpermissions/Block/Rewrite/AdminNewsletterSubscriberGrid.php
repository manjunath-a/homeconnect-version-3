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
class Aitoc_Aitpermissions_Block_Rewrite_AdminNewsletterSubscriberGrid
 extends Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
 {
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('newsletter/subscriber_collection');
        /* @var $collection Mage_Newsletter_Model_Mysql4_Subscriber_Collection */
        $collection
            ->showCustomerInfo(true)
            ->addSubscriberTypeField()
            ->showStoreInfo();

        if($this->getRequest()->getParam('queue', false)) {
            $collection->useQueue(Mage::getModel('newsletter/queue')
                ->load($this->getRequest()->getParam('queue')));
        }
        
        if (!Mage::helper('aitpermissions')->isShowingAllCustomers())
        {
            $role = Mage::getSingleton('aitpermissions/role');
            
            if ($role->isPermissionsEnabled())
            {
                $collection->addFieldToFilter('website_id', array('in' => $role->getAllowedWebsiteIds()));
                $collection->addFieldToFilter('group_id', array('in' => $role->getAllowedStoreIds()));
                $collection->addFieldToFilter('store_id', array('in' => $role->getAllowedStoreViewIds()));
            }
        }
        
        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
    
	protected function _prepareColumns()
	{
		parent::_prepareColumns();

        $role = Mage::getSingleton('aitpermissions/role');

		if ($role->isPermissionsEnabled())
		{
            if (!Mage::helper('aitpermissions')->isShowingAllCustomers())
            {
                if(isset($this->_columns['website']))
                {
                    unset($this->_columns['website']);
                    $allowedWebsiteIds = $role->getAllowedWebsiteIds();

                    if (count($allowedWebsiteIds) > 1)
                    {
                        $websiteFilter = array();
                        foreach ($allowedWebsiteIds as $allowedWebsiteId)
                        {
                            $website = Mage::getModel('core/website')->load($allowedWebsiteId);
                            $websiteFilter[$allowedWebsiteId] = $website->getData('name');
                        }

                        $this->addColumn('website', array(
                            'header' => Mage::helper('customer')->__('Website'),
                            'align' => 'center',
                            'width' => '80px',
                            'type' => 'options',
                            'options' => $websiteFilter,
                            'index' => 'website_id',
                        ));
                    }
                }
                if(isset($this->_columns['group']))
                {
                    unset($this->_columns['group']);
                    $allowedStoreIds = $role->getAllowedStoreIds();
                    if (count($allowedStoreIds) > 1)
                    {
                        $storeFilter = array();
                        foreach ($allowedStoreIds as $allowedStoreId)
                        {
                            $store = Mage::getModel('core/store_group')->load($allowedStoreId);
                            $storeFilter[$allowedStoreId] = $store->getData('name');
                        }

                        $this->addColumn('group', array(
                            'header' => Mage::helper('customer')->__('Store'),
                            'align' => 'center',
                            'width' => '80px',
                            'type' => 'options',
                            'options' => $storeFilter,
                            'index' => 'group_id',
                        ));
                    }
                }
                if(isset($this->_columns['store']))
                {
                    unset($this->_columns['store']);
                    $allowedStoreViewIds = $role->getAllowedStoreViewIds();
                    if (count($allowedStoreViewIds) > 1)
                    {
                        $storeViewFilter = array();
                        foreach ($allowedStoreViewIds as $allowedStoreViewId)
                        {
                            $storeView = Mage::getModel('core/store')->load($allowedStoreViewId);
                            $storeViewFilter[$allowedStoreViewId] = $storeView->getData('name');
                        }

                        $this->addColumn('store', array(
                            'header' => Mage::helper('customer')->__('Store View'),
                            'align' => 'center',
                            'width' => '80px',
                            'type' => 'options',
                            'options' => $storeViewFilter,
                            'index' => 'store_id',
                        ));
                    }
                }                
            }
		}
        
        return $this;
	}    
    
 }