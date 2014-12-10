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

class VDesign_Bookme_Model_Book_Item extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('bookme/book_item');
    }
    
    public function setProduct($product){
    	if(!$product)
    		Mage::throwException('product is null');
    	if($product instanceof Mage_Catalog_Model_Product){
    		$this->setProductId($product->getId());
    	}else{
    		Mage::throwException('not a product instance');
    	}
    }
    
    public function setBook($book){
    	if(!$book)
    		Mage::throwException('book is null');
    	if($book instanceof VDesign_Bookme_Model_Book){
    		$this->setBookId($book->getId());
    	}else{
    		Mage::throwException('not a book instance');
    	}
    }
}