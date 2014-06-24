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
class VDesign_Bookme_Model_Resource_Product_Attribute_Backend_Excludeday
extends Mage_Core_Model_Resource_Db_Abstract{
	
	/**
	 * Initialize connection and define main table
	 *
	 */
	protected function _construct()
	{
		$this->_init('bookme/book_excludeday', 'exday_id');
	}
	
}