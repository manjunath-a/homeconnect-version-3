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
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create connector table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('connector_design')};
DROP TABLE IF EXISTS {$this->getTable('connector_device')};
DROP TABLE IF EXISTS {$this->getTable('connector_banner')};
DROP TABLE IF EXISTS {$this->getTable('connector_payment')};
DROP TABLE IF EXISTS {$this->getTable('connector_app')};
DROP TABLE IF EXISTS {$this->getTable('connector_plugin')};
DROP TABLE IF EXISTS {$this->getTable('connector_key')};
DROP TABLE IF EXISTS {$this->getTable('connector_notice')};
DROP TABLE IF EXISTS {$this->getTable('connector_cms')};

CREATE TABLE {$this->getTable('connector_key')} (
  `key_id` int(11) unsigned NOT NULL auto_increment,
  `key_secret` varchar(255) NULL default '',    
  `website_id` int (11),
  PRIMARY KEY (`key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE {$this->getTable('connector_notice')} (
  `notice_id` int(11) unsigned NOT NULL auto_increment,
  `notice_title` varchar(255) NULL default '',    
  `notice_url` varchar(255) NULL default '',    
  `notice_content` text NULL default '',    
  `notice_sanbox` tinyint(1) NULL default '0',
  `website_id` int (11),
  `device_id` int (11),
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE {$this->getTable('connector_app')} (
  `app_id` int(11) unsigned NOT NULL auto_increment,
  `app_name` varchar(255) NULL default '',  
  `device_id` int (11),
  `expired_time` datetime,
  `status` tinyint(4) NOT NULL default '2',
  `categories` text NULL,
  `website_id` int (11),
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE {$this->getTable('connector_plugin')} (
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

CREATE TABLE {$this->getTable('connector_design')} (
  `design_id` int(11) unsigned NOT NULL auto_increment,
  `theme_color` varchar(255) NULL default '',
  `theme_logo` varchar(255) NULL default '',
  `device_id` int (11) NULL,
  `website_id` int (11),
  PRIMARY KEY (`design_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('connector_device')} (
  `device_id` int(11) unsigned NOT NULL auto_increment,
  `device_token` varchar(255) NOT NULL UNIQUE,   
  `plaform_id` int (11),
  `website_id` int (11),
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('connector_banner')} (
  `banner_id` int(11) unsigned NOT NULL auto_increment,
  `banner_name` varchar(255) NULL, 
  `banner_url` varchar(255) NULL default '',
  `banner_title` varchar(255) NULL,
  `status` int(11) NULL,  
  `website_id` smallint(5) NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('connector_cms')} (
  `cms_id` int(11) unsigned NOT NULL auto_increment,
  `cms_title` varchar(255) NULL, 
  `cms_image` varchar(255) NULL default '', 
  `cms_content` text NULL default '',  
  `cms_status` tinyint(4) NOT NULL default '1',
  `website_id` smallint(5) NULL,
  PRIMARY KEY (`cms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
Mage::helper('connector')->importDesgin();
Mage::helper('connector')->saveDataApp();
$installer->endSetup();

