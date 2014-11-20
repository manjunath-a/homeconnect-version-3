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

class Aitoc_Aitpermissions_Block_Rewrite_AdminCatalogProductEditTabWebsites
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Websites
{
    public function getWebsiteCollection()
    {
    	$collection = Mage::getModel('core/website')->getResourceCollection();

        $role = Mage::getSingleton('aitpermissions/role');

    	if ($role->isPermissionsEnabled())
        {
            $collection->addIdFilter($role->getAllowedWebsiteIds());
        }
        
        return $collection->load();
    }
}