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
class VDesign_Bookme_Model_Product_Option extends Mage_Catalog_Model_Product_Option{
	
	const OPTION_GROUP_MULTIDATE       = 'multidate';
	const OPTION_TYPE_MULTIDATE_TYPE   = 'multidate_type';
	/**
	 * Get group name of option by given option type
	 *
	 * @param string $type
	 * @return string
	 */
	public function getGroupByType($type = null)
	{
		if (is_null($type)) {
			$type = $this->getType();
		}
	
		$group = parent::getGroupByType($type);
		if( $group === '' && $type == self::OPTION_TYPE_MULTIDATE_TYPE ){
			$group = self::OPTION_GROUP_MULTIDATE;
		}
		return $group;
	}
	/**
	 * Group model factory
	 *
	 * @param string $type Option type
	 * @return Mage_Catalog_Model_Product_Option_Group_Abstract
	 */
	public function groupFactory($type)
	{
		if( $type === self::OPTION_TYPE_MULTIDATE_TYPE ){
			return Mage::getModel('bookme/catalog_product_option_type_multidate');
		}
		return parent::groupFactory($type);
	}
}