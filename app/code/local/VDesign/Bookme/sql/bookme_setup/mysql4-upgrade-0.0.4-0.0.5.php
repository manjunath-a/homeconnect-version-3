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

$installer->addAttributeGroup('catalog_product', 'Default', 'Book Setup', 1000);

// protected function _prepareValues($attr)
// {
// 	$data = array(
// 			'backend_model'   => $this->_getValue($attr, 'backend'),
// 			'backend_type'    => $this->_getValue($attr, 'type', 'varchar'),
// 			'backend_table'   => $this->_getValue($attr, 'table'),
// 			'frontend_model'  => $this->_getValue($attr, 'frontend'),
// 			'frontend_input'  => $this->_getValue($attr, 'input', 'text'),
// 			'frontend_label'  => $this->_getValue($attr, 'label'),
// 			'frontend_class'  => $this->_getValue($attr, 'frontend_class'),
// 			'source_model'    => $this->_getValue($attr, 'source'),
// 			'is_required'     => $this->_getValue($attr, 'required', 1),
// 			'is_user_defined' => $this->_getValue($attr, 'user_defined', 0),
// 			'default_value'   => $this->_getValue($attr, 'default'),
// 			'is_unique'       => $this->_getValue($attr, 'unique', 0),
// 			'note'            => $this->_getValue($attr, 'note'),
// 			'is_global'       => $this->_getValue($attr, 'global', 1),
// 	);

// 	return $data;
// }

$installer->addAttribute('catalog_product', 'bookable_qty', array(
		'group' => 'Book Setup',
		'input' => 'text',
		'type' => 'text',
		'label' => 'Quantity',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->addAttribute('catalog_product', 'include_shipping', array(
		'group' => 'Book Setup',
		'input' => 'select',
		'type' => 'int',
		'label' => 'Shipping',
		'source' => 'eav/entity_attribute_source_table',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'option'     => array (
				'values' => array(
						0 => 'enabled',
						1 => 'disabled'
				)
		),
));

$installer->addAttribute('catalog_product', 'bookable_from', array(
		'group' => 'Book Setup',
		'input' => 'date',
		'type' => 'datetime',
		'label' => 'Booking From',
		'backend' => 'eav/entity_attribute_backend_datetime',
		'frontend' => 'eav/entity_attribute_frontend_datetime',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->addAttribute('catalog_product', 'bookable_to', array(
		'group' => 'Book Setup',
		'input' => 'date',
		'type' => 'datetime',
		'label' => 'Booking To',
		'backend' => 'eav/entity_attribute_backend_datetime',
		'frontend' => 'eav/entity_attribute_frontend_datetime',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));


$installer->addAttribute('catalog_product', 'billable_period', array(
		'group' => 'Book Setup',
		'input' => 'select',
		'type' => 'int',
		'label' => 'Billable Period',
		'source' => 'eav/entity_attribute_source_table',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'option'     => array (
				'values' => array(
						0 => 'Session',
						1 => 'Day',
				)
		),
));


$installer->addAttribute('catalog_product', 'exclude_day', array(
		'group' => 'Book Setup',
		'input' => 'text',
		'type' => 'decimal',
		'label' => 'Exclude Days',
		'backend' => 'bookme/product_attribute_backend_excludeday',
		'frontend' => '',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->addAttribute('catalog_product', 'custom_session', array(
		'group' => 'Book Setup',
		'input' => 'text',
		'type' => 'decimal',
		'label' => 'Custom Sessions',
		'backend' => 'bookme/product_attribute_backend_customsession',
		'frontend' => '',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->addAttribute('catalog_product', 'price_rule', array(
		'group' => 'Book Setup',
		'input' => 'text',
		'type' => 'decimal',
		'label' => 'Price Rules',
		'backend' => 'bookme/product_attribute_backend_pricerule',
		'frontend' => '',
		'visible' => 1,
		'required' => 0,
		'user_defined' => 1,
		'searchable' => 0,
		'filterable' => 0,
		'comparable' => 0,
		'visible_on_front' => 0,
		'visible_in_advanced_search' => 0,
		'is_html_allowed_on_front' => 0,
		'apply_to' => 'booking',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));




$installer->endSetup();