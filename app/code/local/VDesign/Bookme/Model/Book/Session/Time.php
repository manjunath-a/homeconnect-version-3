<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to vdesign.support@outlook.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @copyright  Copyright (c) 2014 VDesign
 * @license    End User License Agreement (EULA)
 */

class VDesign_Bookme_Model_Book_Session_Time extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('bookme/book_session_time');
    }
    
    public function setSession($session){
    	if(!$session)
    		Mage::throwException('no product is setted');
    	
    	if($session instanceof VDesign_Bookme_Model_Book_Session){
    		$this->setSessionId($session->getId());
    	}else{
    		Mage::throwException('not a session in book setup');
    	}
    }
}