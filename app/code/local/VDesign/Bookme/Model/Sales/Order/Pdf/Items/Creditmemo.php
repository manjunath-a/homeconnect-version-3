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

class VDesign_Bookme_Model_Sales_Order_Pdf_Items_Creditmemo
extends Mage_Sales_Model_Order_Pdf_Items_Abstract{
	
	/**
	 * Draw item line
	 *
	 */
	public function draw()
	{
		
		date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
		
		$order  = $this->getOrder();
		$item   = $this->getItem();
		$pdf    = $this->getPdf();
		$page   = $this->getPage();
		$lines  = array();
	
		// draw Product name
		$lines[0] = array(array(
				'text' => Mage::helper('core/string')->str_split($item->getName(), 35, true, true),
				'feed' => 35,
		));
	
		// draw SKU
		$lines[0][] = array(
				'text'  => Mage::helper('core/string')->str_split($this->getSku($item), 17),
				'feed'  => 255,
				'align' => 'right'
		);
	
		// draw Total (ex)
		$lines[0][] = array(
				'text'  => $order->formatPriceTxt($item->getRowTotal()),
				'feed'  => 330,
				'font'  => 'bold',
				'align' => 'right',
		);
	
		// draw Discount
		$lines[0][] = array(
				'text'  => $order->formatPriceTxt(-$item->getDiscountAmount()),
				'feed'  => 380,
				'font'  => 'bold',
				'align' => 'right'
		);
	
		// draw QTY
		$lines[0][] = array(
				'text'  => $item->getQty() * 1,
				'feed'  => 445,
				'font'  => 'bold',
				'align' => 'right',
		);
	
		// draw Tax
		$lines[0][] = array(
				'text'  => $order->formatPriceTxt($item->getTaxAmount()),
				'feed'  => 495,
				'font'  => 'bold',
				'align' => 'right'
		);
	
		// draw Total (inc)
		$subtotal = $item->getRowTotal() + $item->getTaxAmount() + $item->getHiddenTaxAmount()
		- $item->getDiscountAmount();
		$lines[0][] = array(
				'text'  => $order->formatPriceTxt($subtotal),
				'feed'  => 565,
				'font'  => 'bold',
				'align' => 'right'
		);
	
		// draw options
		$options = $this->getItemOptions();
		if ($options) {
			foreach ($options as $option) {
				// draw options label
				$lines[][] = array(
						'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']), 40, true, true),
						'font' => 'italic',
						'feed' => 35
				);
	
				if ($option['value']) {
					if (isset($option['print_value'])) {
						$_printValue = $option['print_value'];
					} else {
						$_printValue = strip_tags($option['value']);
					}
					$values = explode(', ', $_printValue);
					
					foreach ($values as $value) {
						$out = array();
						$data = explode(",", $value);
						$from = date("d-m-Y", $data[0]/1000);
						
						if(count($data) == 1 || strtotime($from) < $data[0] / 1000){
							for($i = 0; $i < count($data) - 1; $i++)
								$out[] = Mage::helper('core')->formatDate(date("d-m-Y H:i:s", $data[$i]/1000), 'medium', true);
						}else{
							$to = date("d-m-Y", $data[count($data)-2]/1000);

							$out[] = Mage::helper('core')->formatDate($from, 'medium', false).' - '.Mage::helper('core')->formatDate($to, 'medium', false);	
						}
						foreach ($out as $o)
							$lines[][] = array(
									'text' => Mage::helper('core/string')->str_split($o, 30, true, true),
									'feed' => 40
							);
					}
				}
			}
		}
	
		$lineBlock = array(
				'lines'  => $lines,
				'height' => 20
		);
	
		$page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
		$this->setPage($page);
	}
	
}