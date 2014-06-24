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
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
->newTable($installer->getTable('bookme/book_session'))
->addColumn('session_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
), 'Exclude Specific Day ID')
->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
), 'Product ID')
->addColumn('session_day', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false
), 'session day')
->addColumn('spec_day', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'specific day')
->addForeignKey($installer->getFkName('bookme/book_session', 'entity_id', 'catalog/product', 'entity_id'),
		'entity_id', $installer->getTable('catalog/product'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('Product ID');
$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
->newTable($installer->getTable('bookme/book_session_time'))
->addColumn('time_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
), 'Session Time ID')
->addColumn('session_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
), 'Session ID')
->addColumn('hour', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'session hours')
->addColumn('minute', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'session minute')
->addForeignKey($installer->getFkName('bookme/book_session_time', 'session_id', 'bookme/book_session', 'session_id'),
		'session_id', $installer->getTable('bookme/book_session'), 'session_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('Session ID');
$installer->getConnection()->createTable($table);

$installer->endSetup();