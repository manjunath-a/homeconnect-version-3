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
class VDesign_Bookme_Adminhtml_BookController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
    	
        //$this->_title($this->__('Sales'))->_title($this->__('Bookme'));
        $this->loadLayout();
        //$this->_setActiveMenu('sales/sales');
        $this->_addContent($this->getLayout()->createBlock('bookme/adminhtml_book'));
        $this->renderLayout();
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('bookme/adminhtml_book_grid')->toHtml()
        );
    }
    public function newAction(){
    	
    	$request = Mage::app()->getResponse()
    	->setRedirect(Mage::helper('adminhtml')->getUrl('adminhtml/sales_order_create/index'));

    }

}