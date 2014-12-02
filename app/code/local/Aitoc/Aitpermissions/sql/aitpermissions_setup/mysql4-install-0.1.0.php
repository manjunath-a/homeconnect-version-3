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

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('aitoc_aitpermissions_advancedrole')} (
  `advancedrole_id` smallint(5) unsigned NOT NULL auto_increment,
  `role_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `category_ids` text NOT NULL,
  PRIMARY KEY  (`advancedrole_id`),
  UNIQUE KEY `role_id` (`role_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

");

$installer->endSetup();