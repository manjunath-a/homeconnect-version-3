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

class Aitoc_Aitpermissions_Block_Adminhtml_Options extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('aitpermissions/options.phtml');
    }

    public function canEditGlobalAttributes()
    {
        return Mage::getModel('aitpermissions/advancedrole')->canEditGlobalAttributes($this->_getRoleId());
    }

    public function canEditOwnProductsOnly()
    {
        return Mage::getModel('aitpermissions/advancedrole')->canEditOwnProductsOnly($this->_getRoleId());
    }

    private function _getRoleId()
    {
        return Mage::app()->getRequest()->getParam('rid');
    }
}