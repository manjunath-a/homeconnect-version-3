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

ALTER TABLE `{$this->getTable('aitoc_aitpermissions_advancedrole')}` ADD `website_id` SMALLINT( 5 ) UNSIGNED NOT NULL AFTER `role_id` ;

ALTER TABLE `{$this->getTable('aitoc_aitpermissions_advancedrole')}` ADD INDEX ( `website_id` ) ;

");

$installer->endSetup();