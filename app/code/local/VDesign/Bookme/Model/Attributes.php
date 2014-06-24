<?php


class VDesign_Bookme_Model_Attributes extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions(){
		return array(
				'exclude_day' => 'bookme/adminhtml_catalog_product_edit_tab_day_exclude',
				'custom_session' => 'bookme/adminhtml_catalog_product_edit_tab_customsession',
				'price_rule' => 'bookme/adminhtml_catalog_product_edit_tab_price_rule'
		);
	}
}