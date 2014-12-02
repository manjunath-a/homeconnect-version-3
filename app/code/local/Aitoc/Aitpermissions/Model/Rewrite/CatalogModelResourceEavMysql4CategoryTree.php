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
* @copyright  Copyright (c) 2012 AITOC, Inc.
*/

class Aitoc_Aitpermissions_Model_Rewrite_CatalogModelResourceEavMysql4CategoryTree
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Tree
{
    protected function _updateAnchorProductCount(&$data)
    {
        foreach ($data as $key => $row)
        {
            if (isset($row['is_anchor']) && 0 === (int)$row['is_anchor'])
            {
                $data[$key]['product_count'] = $row['self_product_count'];
            }
        }
    }
}