<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Madapter Block
 * 
 * @category 	Simi
 * @package 	Simi_Popup
 * @author  	Simi Developer
 */
class Simi_Popup_Block_Popup extends Mage_Core_Block_Template {
     

    public function _prepareLayout() {
		$this->isMobile();
        return parent::_prepareLayout();
    }
    

    public function isMobile() {

        if (!function_exists('getallheaders')) {

            function getallheaders() {
                $head = array();
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                        $head[$name] = $value;
                    } else if ($name == "CONTENT_TYPE") {
                        $head["Content-Type"] = $value;
                    } else if ($name == "CONTENT_LENGTH") {
                        $head["Content-Length"] = $value;
                    }
                }
                return $head;
            }

        }		
        $head = getallheaders();
        if (isset($head['Mobile-App']))
            return false;
        if ($_SERVER["HTTP_USER_AGENT"]) {
            $user_agent = $_SERVER["HTTP_USER_AGENT"];
			if(Mage::getSingleton('core/session')->getSessionSimiPopup()==null){
				if (strstr($user_agent, 'iPhone') || strstr($user_agent, 'iPod')){
				$this->setTemplate('popup/popup.phtml');
				}			
				elseif(strstr($user_agent, 'Android')){
					$this->setTemplate('popup/popup2.phtml');
				}
				elseif(strstr($user_agent, 'iPad')){
					$this->setTemplate('popup/popup1.phtml');
				}            
			}            			
            Mage::getSingleton('core/session')->setSessionSimiPopup(1);
            return true;            
        }
        return FALSE;
    }

}