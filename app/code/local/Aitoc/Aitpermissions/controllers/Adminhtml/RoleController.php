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
* @copyright  Copyright (c) 2009 AITOC, Inc. 
*/
class Aitoc_Aitpermissions_Adminhtml_RoleController extends Mage_Adminhtml_Controller_Action
{
    public function duplicateAction()
    {
        $roleModel    = Mage::getModel('admin/roles');
        $aitRoleModel = Mage::getModel('aitpermissions/advancedrole');
        $loadRole     = $roleModel->load($this->getRequest()->getParam('rid'));
        $roleName     = $loadRole->getRoleName();
        $ruleModel    = Mage::getModel("admin/rules");
        $loadRuleCollection = $ruleModel->getCollection()->addFilter('role_id',$this->getRequest()->getParam('rid'));
        //echo "<pre>"; print_r($loadRuleCollection->getSize()); exit;
        $loadAitRoleCollection  = $aitRoleModel->getCollection()->addFilter('role_id',$this->getRequest()->getParam('rid'));
        try
        {
            $roleModel->setId(null)
                ->setName('Copy of '.$loadRole->getRoleName())
                ->setPid($loadRole->getParentId())
                ->setTreeLevel($loadRole->getTreeLevel())
                ->setType($loadRole->getType())
                ->setUserId($loadRole->getUserId())
             ->save();
//            foreach (explode(",",$roleModel->getUserId()) as $nRuid) {
//                $this->_addUserToRole($nRuid, $roleModel->getId());
//            }
            
            foreach ($loadRuleCollection as $rule)
            {
                $ruleModel
                    ->setData($rule->getData())
                    ->setRuleId(null)
                    ->setRoleId($roleModel->getId())
                ->save();
            }
            $newRoleId =  $roleModel->getRoleId();
            foreach ($loadAitRoleCollection as $loadAitRole)
            {
                $aitRoleModel->setId(null)
                    ->setRoleId($newRoleId)
                    ->setWebsiteId($loadAitRole->getWebsiteId())
                    ->setStoreId($loadAitRole->getStoreId())
                    ->setStoreviewIds($loadAitRole->getStoreviewIds())
                    ->setCategoryIds($loadAitRole->getCategoryIds())
                    ->setCanEditGlobalAttr($loadAitRole->getCanEditGlobalAttr())
                    ->setCanEditOwnProductsOnly($loadAitRole->getCanEditOwnProductsOnly())
                ->save();
            }
        }
        catch (Exception $e)
        {
            $this->_getSession()->addError($this->__("Role %s wasn't duplicated. %s",$roleName,$e->getMessage()));
        }   
        $this->_getSession()->addSuccess($this->__("Role %s was duplicated",$roleName));
        $this->_redirect('adminhtml/permissions_role/index');
        
        return $this;
    }
    
//    protected function _addUserToRole($userId, $roleId)
//    {
//        $user = Mage::getModel("admin/user")->load($userId);
//        $user->setRoleId($roleId)->setUserId($userId);
//
//        if( $user->roleUserExists() === true ) {
//            return false;
//        } else {
//            $user->add();
//            return true;
//        }
//    }
}