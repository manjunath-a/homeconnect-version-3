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

class Aitoc_Aitpermissions_Model_Rewrite_CatalogCategory extends Mage_Catalog_Model_Category
{
    protected function _beforeSave()
    {
        if (!$this->getId() && !Mage::registry('aitemails_category_is_new'))
        {
            Mage::register('aitemails_category_is_new', true);
        }

        $role = Mage::getSingleton('aitpermissions/role');
        if (!Mage::registry('aitpermissions_category_update')) {

            if(!$role->isAllowedToEditCategory($this)) {
                Mage::register('aitoc_catalog_catagory_clear_session_data', true);
                throw new Mage_Core_Exception(
                    Mage::helper('aitpermissions')->__('You do not have permissions to update "%s" category.', $this->getName())
                );
            }
        }

        $generalSection = Mage::app()->getRequest()->getParam('general');
        if(!isset($generalSection) || !is_array($generalSection) || !isset($generalSection['is_active']))
            return parent::_beforeSave();

        if(isset($generalSection['path'])) {
            $parent_category_id = $this->_getParentCategoryFromPath($generalSection['path']);
            if($role->isPermissionsEnabled() && !in_array($parent_category_id, $role->getAllowedCategoryIds())) {
                throw new Mage_Core_Exception(
                    Mage::helper('aitpermissions')->__('You are not allowed to save category in "%s" branch.', Mage::getModel('catalog/category')->load($parent_category_id)->getName())
                );
            }
        }

        //}
        

        if ($role->isPermissionsEnabled()
            && Mage::getStoreConfig('admin/sucategories/enable')
            && !$this->getId())
        {
            $this->setAitCategoryApproveStatus(Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_AWAITING);
        }

        if(!$role->isPermissionsEnabled() && $this->getId() && Mage::getModel('aitpermissions/approvecategory')->isCategoryAwaitingApproving($this->getId())){
            switch($generalSection['is_active']){
                case '0':
                    $this->setAitCategoryApproveStatus(Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_NOT_APPROVED);
                    break;                
                case '1':
                    $this->setAitCategoryApproveStatus(Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_APPROVED);
                    break;
                default:                    
                    break;
                }
            }
        
        return parent::_beforeSave();
    }

    protected function _getParentCategoryFromPath($path) {
        if(!is_numeric($path)) {
            $path = explode('/', $path);
            $category_id = array_pop($path);//should be current category_id
            if($this->getId() && $category_id == $this->getId()) {
                $category_id = array_pop($path);
            }
            $path = $category_id;
        }
        $path = (int)$path;
        return $path;
    }

    protected function _afterSave()
    {
        if ($this->getData('entity_id') && 
            ($this->getAitCategoryApproveStatus() === Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_AWAITING || 
             $this->getAitCategoryApproveStatus() === Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_NOT_APPROVED || 
             $this->getAitCategoryApproveStatus() === Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_APPROVED
            ))
        {
            switch($this->getAitCategoryApproveStatus()){
                case Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_NOT_APPROVED:
                    Mage::getModel('aitpermissions/approvecategory')->disapprove($this->getData('entity_id'));
                    break;
                case Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_APPROVED:
                    Mage::getModel('aitpermissions/approvecategory')->approve($this->getData('entity_id'));
                    break;
                case Aitoc_Aitpermissions_Model_Approvecategory::AIT_CATEGORY_STATUS_AWAITING:
                    Mage::getModel('aitpermissions/approvecategory')->add($this->getData('entity_id'));
                    Mage::getModel('aitpermissions/notification')->sendCategoryForApproving($this);
                    break;
                default:
                    break;            
            }

            $this->setAitCategoryApproveStatus('');
        }

        $role = Mage::getSingleton('aitpermissions/role');

        if ($role->isPermissionsEnabled())
        {
            $role->addAllowedCategoryId($this->getId(), $this->_getCurrentStoreId());
            
            if (true === Mage::registry('aitemails_category_is_new'))
            {
                Mage::unregister('aitemails_category_is_new');
                Mage::register('aitpermissions_category_update', true);
                $backUpProducts = $this->getPostedProducts();
                $this->setPostedProducts(null);
                $this->setStoreId(0);

                if(!Mage::getStoreConfig('admin/sucategories/enable')){
                    $this->setIsActive(0);
                }
                $this->save();
                $this->setPostedProducts($backUpProducts);
                Mage::unregister('aitpermissions_category_update');
            }
        }
        
        return parent::_afterSave();
    }

    private function _getCurrentStoreId()
    {
        $storeviewId = Mage::app()->getRequest()->getParam('store');
        return Mage::getModel('core/store')->load($storeviewId)->getGroupId();
    }
}