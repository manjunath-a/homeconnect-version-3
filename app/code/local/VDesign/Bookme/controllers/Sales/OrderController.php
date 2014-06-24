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
class VDesign_Bookme_Sales_OrderController
extends Mage_Adminhtml_Controller_Action
{
	
	public function viewAction(){
		$response = Mage::app()->getResponse();
		$URL = Mage::helper("adminhtml")->getUrl("adminhtml/sales_order/view/", 
				array(
						"order_id" => $this->getRequest()->getParam('order_id'),
						"key" => $this->getRequest()->getParam('key')
						
				)
		);
		
		$response->setRedirect(
				$URL
		);
	}
		
		public function exportCsvAction()
		{
			$fileName = 'bookind_data.csv';
			$grid = $this->getLayout()->createBlock('bookme/adminhtml_book_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		}
		public function exportExcelAction()
		{
			$fileName = 'bookind_data.xml';
			$grid = $this->getLayout()->createBlock('bookme/adminhtml_book_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
		
	
}