<?php



class FME_Bookingreservation_Model_Sales_Order_Total_Invoice_Homeservice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		$order = $invoice->getOrder();
		$feeAmountLeft = $order->getHomeserviceAmount(); - $order->getHomeserviceAmountInvoiced();
		$baseFeeAmountLeft = $order->getBaseHomeserviceAmount(); - $order->getBaseHomeserviceAmountInvoiced();
                
		//if (abs($baseFeeAmountLeft) < $invoice->getBaseGrandTotal()) {
			$invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmountLeft);
			$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmountLeft);
		/*} else {
			$feeAmountLeft = $invoice->getGrandTotal() * -1;
			$baseFeeAmountLeft = $invoice->getBaseGrandTotal() * -1;

			$invoice->setGrandTotal(0);
			$invoice->setBaseGrandTotal(0);
		}
		*/
                
		$invoice->setHomeserviceAmount($feeAmountLeft);
		$invoice->setBaseHomeserviceAmount($baseFeeAmountLeft);
                
		return $this;
	}
}
