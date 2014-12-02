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

class Aitoc_Aitpermissions_Block_Rewrite_AdminReportShopcartProductGrid
    extends Mage_Adminhtml_Block_Report_Shopcart_Product_Grid
{
    protected function _prepareCollection()
    {
        $role = Mage::getSingleton('aitpermissions/role');
        
        $collection = Mage::getResourceModel('reports/quote_collection');
        if(version_compare(Mage::getVersion(), '1.6.0.0', '<'))
		{
		    $collection->prepareForProductsInCarts()
			    ->setSelectCountSqlType(Mage_Reports_Model_Mysql4_Quote_Collection::SELECT_COUNT_SQL_TYPE_CART);
        }
        else
        {
		    $collection->prepareForProductsInCarts()
			    ->setSelectCountSqlType(Mage_Reports_Model_Resource_Quote_Collection::SELECT_COUNT_SQL_TYPE_CART);
		}
		
        if ($role->isPermissionsEnabled())
        {
            if (!Mage::helper('aitpermissions')->isShowingAllProducts())
            {
                if ($role->isScopeStore())
                {
                    $collection->getSelect()->joinLeft(array(
                        'product_cat' => Mage::getSingleton('core/resource')->getTableName('catalog_category_product')),
                        'product_cat.product_id = e.entity_id',
                        array()
                    );

                    $collection->getSelect()->where(
                        ' product_cat.category_id in (' . join(',', $role->getAllowedCategoryIds()) . ')
                        or product_cat.category_id IS NULL '
                    );

                    $collection->getSelect()->distinct(true);
                }
                
                if ($role->isScopeWebsite())
                {
                    $collection->addStoreFilter($role->getAllowedStoreviewIds());
                }
            }           
        }

        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
               
    }
    
    public function getRowUrl($row)
    {
        $role = Mage::getSingleton('aitpermissions/role');
        if ($role->isPermissionsEnabled())
        {
            $stores = $role->getAllowedStoreviewIds();
            return $this->getUrl('*/catalog_product/edit', array(
                'store'=>$stores[0], 
                'id'=>$row->getEntityId()
            ));
        }
        return parent::getRowUrl($row);
    }
}