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
 * @package 	Magestore_Paypalmobile
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create paypalmobile table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('paypalmobile')};

CREATE TABLE {$this->getTable('paypalmobile')} (
  `paypalmobile_id` int(11) unsigned NOT NULL auto_increment,
  `transaction_id` varchar(255) NULL, 
  `transaction_name` varchar(255) NULL,
  `transaction_email` varchar(255) NULL,
  `status` varchar(255) NULL,
  `amount` varchar(255) NULL,    
  `currency_code` varchar(255) NULL,  
  `transaction_dis` varchar(255) NULL,
  `order_id` int(11) NULL,  
  PRIMARY KEY (`paypalmobile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

