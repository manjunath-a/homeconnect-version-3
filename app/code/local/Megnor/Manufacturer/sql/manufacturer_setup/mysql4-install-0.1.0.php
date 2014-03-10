<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('manufacturer')};
CREATE TABLE {$this->getTable('manufacturer')} (
  `manufacturer_id` int(11) unsigned NOT NULL auto_increment,
  `manufacturer_name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL DEFAULT '',  
  `urlkey` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  `position` int(11) NOT NULL DEFAULT '0',  
  PRIMARY KEY (`manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 