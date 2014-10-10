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
->newTable($installer->getTable('bookme/book_excludeday'))
->addColumn('exday_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
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
->addColumn('period_type', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false
), 'period type')
->addColumn('from_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'from date')
->addColumn('to_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(), 'to date')
->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'value')
->addForeignKey($installer->getFkName('bookme/book_excludeday', 'entity_id', 'catalog/product', 'entity_id'),
				'entity_id', $installer->getTable('catalog/product'), 'entity_id',
				Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
				->setComment('Product ID');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
->newTable($installer->getTable('bookme/book_pricerule'))
->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
), 'Price rule ID')
->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
), 'Product ID')
->addColumn('type', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Type')
->addColumn('value', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Value')
->addColumn('value_type', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Value Type')
->addColumn('move', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Move')
->addColumn('amount', Varien_Db_Ddl_Table::TYPE_DOUBLE, null, array(), 'amount')
->addColumn('amount_type', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Amount Type')
->addForeignKey($installer->getFkName('bookme/book_pricerule', 'entity_id', 'catalog/product', 'entity_id'),
		'entity_id', $installer->getTable('catalog/product'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('Product ID');
$installer->getConnection()->createTable($table);

$installer->endSetup();
