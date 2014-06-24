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
->newTable($installer->getTable('bookme/book'))
->addColumn('book_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
), 'Book ID')
->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => true,
), 'Order ID')
->addColumn('customer_firstname', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false
), 'Customer firstname')
->addColumn('customer_lastname', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false
), 'Customer lastname')
->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false
), 'Customer email')
->addColumn('customer_phone', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false
), 'Customer phone')
->addColumn('created', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
		'nullable'  => false
), 'Time of book');
$installer->getConnection()->createTable($table);


$installer->run(
		"ALTER TABLE ".$installer->getTable('bookme/book')."
  MODIFY COLUMN `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;"
		);



$table = $installer->getConnection()
->newTable($installer->getTable('bookme/book_item'))
->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
), 'Item ID')
->addColumn('book_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false
), 'Book ID')
->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
), 'Product Id')
->addColumn('qty', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false
), 'Qty of booked product')
->addColumn('booked_from', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
		'nullable'  => false
), 'booked from time')
->addColumn('booked_to', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
		'nullable'  => false
), 'booked to time')
->addForeignKey($installer->getFkName('bookme/book_item', 'book_id', 'bookme/book', 'book_id'),
		'book_id', $installer->getTable('bookme/book'), 'book_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
->addForeignKey($installer->getFkName('bookme/book_item', 'product_id', 'catalog/product', 'entity_id'),
				'product_id', $installer->getTable('catalog/product'), 'entity_id',
				Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('Booking item entity');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
->newTable($installer->getTable('bookme/book_time'))
->addColumn('time_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
), 'Item ID')
->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false
), 'Book ID')
->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
), 'Product Id')
->addColumn('hours', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false
), 'Qty of booked product')
->addColumn('minutes', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false
), 'booked from time')
->addColumn('seconds', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => false
), 'booked to time')
->addForeignKey($installer->getFkName('bookme/book_time', 'entity_id', 'catalog/product', 'entity_id'),
		'entity_id', $installer->getTable('catalog/product'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->addForeignKey($installer->getFkName('bookme/book_time', 'attribute_id', 'eav/attribute', 'attribute_id'),
				'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
				Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
				->setComment('Booking item entity');
$installer->getConnection()->createTable($table);

$installer->endSetup();
