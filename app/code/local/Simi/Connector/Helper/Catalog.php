<?php

/**
 * Simi
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Helper
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Helper_Catalog extends Mage_Core_Helper_Abstract {

    public function getSortOption($sort_option) {
        $sort = array();
        switch ($sort_option) {
            case 1:
                $sort[0] = 'price';
                $sort[1] = 'ASC';
                break;
            case 2:
                $sort[0] = 'price';
                $sort[1] = 'DESC';
                break;
            case 3:
                $sort[0] = 'name';
                $sort[1] = 'ASC';
                break;
            case 4:
                $sort[0] = 'name';
                $sort[1] = 'DESC';
                break;
            default :
                $sort = null;
        }
        return $sort;
    }

    public function getCartParams($infoProduct, $type) {
        $params = array();
        $params['product'] = $infoProduct->product_id;
        $params['related_product'] = '';
        $params['qty'] = $infoProduct->product_qty;

        $this->setCartParams($infoProduct, $params, $type);

        return $params;
    }
	
	public function setOptionsSimple($options, &$options_att){
		if ($options->option_type == 'single') {
			$options_att[$options->option_type_id] = $options->option_id;
        } else if ($options->option_type == 'text') {
			$options_att[$options->option_type_id] = $options->option_value;
		} else if ($options->option_type == 'time') {
			$value = array();
            $dateTime = strtotime('2014-07-02 ' . $options->option_value);
            $value['hour'] = date('h', $dateTime);
            $value['minute'] = date('i', $dateTime);
            $value['day_part'] = date('a', $dateTime);

            $options_att[$options->option_type_id] = $value;
        } else if ($options->option_type == 'date') {
			$value = array();
            $dateTime = strtotime($options->option_value);
            $value['year'] = date('Y', $dateTime);
            $value['month'] = date('m', $dateTime);
            $value['day'] = date('d', $dateTime);

            $options_att[$options->option_type_id] = $value;
        } else if ($options->option_type == 'date_time') {
			$value = array();
            $dateTime = strtotime($options->option_value);
            $value['hour'] = date('h', $dateTime);
            $value['minute'] = date('i', $dateTime);
            $value['day_part'] = date('a', $dateTime);

			$value['year'] = date('Y', $dateTime);
            $value['month'] = date('m', $dateTime);
            $value['day'] = date('d', $dateTime);

			$options_att[$options->option_type_id] = $value;
        } else {
			if (!isset($options_att[$options->option_type_id])) {
				$options_att[$options->option_type_id] = array();
            }
            $options_att[$options->option_type_id][] = $options->option_id;
        }				
	}
	
    public function setCartParams($infoProduct, &$params, $type) {
        try {
            if (!isset($type)) {
                return;
            }
            if ($type == 'simple' || $type == 'virtual') {
                $options_att = array();
                if (isset($infoProduct->options)) {
                    foreach ($infoProduct->options as $options) {
                        $this->setOptionsSimple($options, $options_att);
                    }
                    $params['options'] = $options_att;
                }
                return;
            } elseif ($type == 'grouped') {
                $options_att = array();
                foreach ($infoProduct->options as $option) {
                    $options_att[$option->option_id] = $option->option_qty;
                }
                $params['super_group'] = $options_att;
                return;
            } elseif ($type == 'bundle') {
                $options_att = array();
                foreach ($infoProduct->options as $options) {
                    if ($options->option_type == 'single')
                        $options_att[$options->option_type_id] = $options->option_id;
                    else
                        $options_att[$options->option_type_id][] = $options->option_id;
                }
                $params['bundle_option'] = $options_att;
                $params['bundle_option_qty'] = array();
                return;
            } elseif ($type == 'configurable') {
                $options_att = array();
				$options_custom = array();
                foreach ($infoProduct->options as $options) {                    
					if(!isset($options->dependence_option_ids)){
						$this->setOptionsSimple($options, $options_custom);
					}else{
						$options_att[$options->option_type_id] = $options->option_id;
					}
                }
                $params['super_attribute'] = $options_att;
				if(count($options_custom)){
					$params['options'] = $options_custom;
				}
                return;
            } else {
                return;
            }
        } catch (Exception $e) {
            Mage::log($e);
        }
    }

    public function checkOptions($products, $optionId, $attributes) {
        foreach ($products as $product) {
            foreach ($attributes as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $code = $productAttribute->getAttributeCode();
                if ($product->getData($code) == $optionId) {
                    return $product;
                }
            }
        }
        return null;
    }

    public function setOptions(&$options, $product, $cacheAttribute) {
        foreach ($cacheAttribute as $code => $value) {
            $options[] = $product->getData($code);
        }
    }

    public function setTax(&$data, $label, $value) {
        $data[] = array(
            'label' => $label,
            'value' => $value,
        );
    }
}