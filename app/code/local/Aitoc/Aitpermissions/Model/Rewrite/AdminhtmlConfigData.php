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

class Aitoc_Aitpermissions_Model_Rewrite_AdminhtmlConfigData extends Mage_Adminhtml_Model_Config_Data
{

    public function load()
    {
        if ($this->getSection() != Mage::app()->getRequest()->getParam('section')) {
            $this->setSection(Mage::app()->getRequest()->getParam('section'));
            $this->_configData = null;
        }
        return parent::load();
    }
}