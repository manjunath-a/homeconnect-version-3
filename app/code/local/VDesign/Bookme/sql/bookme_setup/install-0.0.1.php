<?
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

$fieldList = array(
		'price',
		'special_price',
		'tax_class_id',
		'tier_price',
		'group_price',
		'special_from_date',
		'special_to_date',
		'msrp_enabled',
		'msrp_display_actual_price_type',
		'msrp',
		'enable_googlecheckout'
);

foreach ($fieldList as $field) {
	$applyTo = split(',', $installer->getAttribute('catalog_product', $field, 'apply_to'));
	if (!in_array('booking', $applyTo)) {
		$applyTo[] = 'booking';
		$installer->updateAttribute('catalog_product', $field, 'apply_to', join(',', $applyTo));
	}
}

$installer->endSetup();