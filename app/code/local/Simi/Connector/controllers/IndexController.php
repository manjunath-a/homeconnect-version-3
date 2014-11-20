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
 * @package 	Magestore_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Index Controller
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_IndexController extends Mage_Core_Controller_Front_Action {

    public function apiAction() {
        $key = $this->getRequest()->getParam('key');
        $modelKey = Mage::getModel('connector/key')->getCollection()->addFieldToFilter('key_secret', $key)->getFirstItem();
        if ($modelKey->getId() != NULL) {
            $webId = $modelKey->getWesiteId();
            $app_list = Mage::getModel('connector/simicart_api')->getListApp($key);
            if ($app_list->status == "FAIL") {
                echo Mage::helper('connector')->__('Authorize secret key is incorrect');
                exit();
            } else {
                Mage::getModel('connector/app')->saveList($app_list, $webId);
                Mage::getModel('connector/plugin')->saveList($app_list, $webId);
                echo Mage::helper('connector')->__('Update information successfully');
                exit();
            }
        } else {
            echo Mage::helper('connector')->__('Authorize secret key is incorrect');
            exit();
        }
    }

    public function testAction() {
        $arr = array();
        $arr['is_install'] = "1";
        $key = $this->getRequest()->getParam('key');
        if ($key == null)
            $key = "xxxxxxxxx";
        $website_id = Mage::app()->getWebsite()->getId();

        $data = Mage::getModel('connector/key')->getCollection()
                ->addFieldToFilter('key_secret', $key)
                ->addFieldToFilter('website_id', $website_id);

        if (count($data->getFirstItem()->getData())) {
            $arr["website_key"] = "1";
        } else {
            $arr["website_key"] = "0";
        }

        echo json_encode($arr);
        exit();
    }

    public function installCategoriesAction() {
        $setup = new Mage_Core_Model_Resource_Setup();
        // Zend_Debug::dump(get_class($setup));die();	
        $installer = $setup;
        $installer->startSetup();
        $installer->run("		ALTER TABLE {$setup->getTable('connector_app')}			ADD COLUMN `categories` text NULL;		");
        $installer->endSetup();
        echo "success";
    }

    public function installCmsAction() {
        $setup = new Mage_Core_Model_Resource_Setup();
        $installer = $setup;
        $installer->startSetup();
        $installer->run("		
				DROP TABLE IF EXISTS {$setup->getTable('connector_cms')};
				CREATE TABLE {$setup->getTable('connector_cms')} (
			  `cms_id` int(11) unsigned NOT NULL auto_increment,
			  `cms_title` varchar(255) NULL, 
			  `cms_image` varchar(255) NULL default '', 
			  `cms_content` text NULL default '',  
			  `cms_status` tinyint(4) NOT NULL default '1',
			  `website_id` smallint(5) NULL,
			  PRIMARY KEY (`cms_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;		");
        $installer->endSetup();
        echo "success";
    }
	
	public function installDbAction(){
		$setup = new Mage_Core_Model_Resource_Setup('core_setup');
        $installer = $setup;
        $installer->startSetup();
		$installer->run("

			DROP TABLE IF EXISTS {$setup->getTable('connector_design')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_device')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_banner')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_payment')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_app')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_plugin')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_cms')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_key')};
			DROP TABLE IF EXISTS {$setup->getTable('connector_notice')};

			CREATE TABLE {$setup->getTable('connector_key')} (
			  `key_id` int(11) unsigned NOT NULL auto_increment,
			  `key_secret` varchar(255) NULL default '',    
			  `website_id` int (11),
			  PRIMARY KEY (`key_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

			CREATE TABLE {$setup->getTable('connector_notice')} (
			  `notice_id` int(11) unsigned NOT NULL auto_increment,
			  `notice_title` varchar(255) NULL default '',    
			  `notice_url` varchar(255) NULL default '',    
			  `notice_content` text NULL default '',    
			  `notice_sanbox` tinyint(1) NULL default '0',
			  `website_id` int (11),
			  `device_id` int (11),
			  PRIMARY KEY (`notice_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

			CREATE TABLE {$setup->getTable('connector_app')} (
			  `app_id` int(11) unsigned NOT NULL auto_increment,
			  `app_name` varchar(255) NULL default '',  
			  `device_id` int (11),
			  `expired_time` datetime,
			  `status` tinyint(4) NOT NULL default '2',
			  `categories` text NULL,
			  `website_id` int (11),
			  PRIMARY KEY (`app_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

			CREATE TABLE {$setup->getTable('connector_plugin')} (
			  `plugin_id` int(11) unsigned NOT NULL auto_increment,
			  `plugin_name` varchar(255) NULL default '',  
			  `plugin_version` int (11),
			  `plugin_code` int (11) NOT NULL UNIQUE,
			  `expired_time` datetime,  
			  `status` tinyint(4) NOT NULL default '0',
			  `plugin_sku` varchar(255),
			  `website_id` int (11),
			  `device_id` int(11),   
			  PRIMARY KEY (`plugin_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

			CREATE TABLE {$setup->getTable('connector_design')} (
			  `design_id` int(11) unsigned NOT NULL auto_increment,
			  `theme_color` varchar(255) NULL default '',
			  `theme_logo` varchar(255) NULL default '',
			  `device_id` int (11) NULL,
			  `website_id` int (11),
			  PRIMARY KEY (`design_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			CREATE TABLE {$setup->getTable('connector_device')} (
			  `device_id` int(11) unsigned NOT NULL auto_increment,
			  `device_token` varchar(255) NOT NULL UNIQUE,   
			  `plaform_id` int (11),
			  `website_id` int (11),
			  PRIMARY KEY (`device_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			CREATE TABLE {$setup->getTable('connector_banner')} (
			  `banner_id` int(11) unsigned NOT NULL auto_increment,
			  `banner_name` varchar(255) NULL, 
			  `banner_url` varchar(255) NULL default '',
			  `banner_title` varchar(255) NULL,
			  `status` int(11) NULL,  
			  `website_id` smallint(5) NULL,
			  PRIMARY KEY (`banner_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			CREATE TABLE {$setup->getTable('connector_cms')} (
			  `cms_id` int(11) unsigned NOT NULL auto_increment,
			  `cms_title` varchar(255) NULL, 
			  `cms_image` varchar(255) NULL default '', 
			  `cms_content` text NULL default '',  
			  `cms_status` tinyint(4) NOT NULL default '1',
			  `website_id` smallint(5) NULL,
			  PRIMARY KEY (`cms_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			");
        $installer->endSetup();
        echo "success";
	}

}