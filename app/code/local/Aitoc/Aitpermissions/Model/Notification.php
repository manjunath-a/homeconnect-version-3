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

class Aitoc_Aitpermissions_Model_Notification extends Mage_Core_Model_Abstract
{
    const XML_PATH_EMAIL_SENDER = 'contacts/email/sender_email_identity';
    
    public function send($product)
    {
        $suEmail = Mage::getStoreConfig('admin/su/email');

        if ('' == $suEmail)
        {
             $suEmail = Mage::getStoreConfig('trans_email/ident_sales/email');
        }

        $vars = (array)$this->_prepareVars($product);
        
        $name = 'Advanced Permissions Notification';
        $storeId = $product->getStoreId();
        
        Mage::getSingleton('core/translate')->setTranslateInline(false);
        
        $mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
        $mailTemplate->setTemplateSubject($name);
        $emailId = Mage::getStoreConfig('admin/su/template', $storeId);

        $mailTemplate->sendTransactional(
            $emailId, 
            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER, $storeId),
            $suEmail,
            $name,
            $vars
        );

        Mage::getSingleton('core/translate')->setTranslateInline(true);

        return $mailTemplate->getSentSuccess();
    }
    
    protected function _prepareVars($product)
    {
        return array(
            'product_name' => $product->getName(),
            'product_sku' => $product->getSku(),
            'product_price' => $product->getPrice(),
            'admin_name' => Mage::getSingleton('admin/session')->getUser()->getName(),
            'website' => Mage::getBaseUrl(),
        );
    }   

    /**
     * @param Mage_Catalog_Model_Category $category     
     */
    public function sendCategoryForApproving(Mage_Catalog_Model_Category $category)
    {
        $suEmail = Mage::getStoreConfig('admin/sucategories/email');

        if ('' == $suEmail)
        {
             $suEmail = Mage::getStoreConfig('trans_email/ident_sales/email');
        }

        $vars = (array)$this->_prepareVarsForCategoryApproving($category);
        
        $name = 'Advanced Permissions Notification';
        $storeId = $category->getStoreId();
      
        Mage::getSingleton('core/translate')->setTranslateInline(false);        
        $mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
        $mailTemplate->setTemplateSubject($name);
        $emailId = Mage::getStoreConfig('admin/sucategories/template', $storeId);
        $mailTemplate->sendTransactional(
            $emailId, 
            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER, $storeId),
            $suEmail,
            $name,
            $vars
        );
        Mage::getSingleton('core/translate')->setTranslateInline(true);

        return $mailTemplate->getSentSuccess();
    }
    
    /**
     * @param Mage_Catalog_Model_Category $category     
     */
    protected function _prepareVarsForCategoryApproving(Mage_Catalog_Model_Category $category)
    {
        return array(
            'category_name' => $category->getName(),
            'category_id' => $category->getId(),            
            'admin_name' => Mage::getSingleton('admin/session')->getUser()->getName(),
            'website' => Mage::getBaseUrl(),
        );
    }
}