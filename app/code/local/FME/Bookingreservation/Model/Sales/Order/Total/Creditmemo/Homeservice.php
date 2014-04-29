<?php



class FME_Bookingreservation_Model_Sales_Order_Total_Creditmemo_Homeservice extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
		$order = $creditmemo->getOrder();
		$feeAmountLeft = $order->getHomeserviceAmountInvoiced() - $order->getHomeserviceAmountRefunded();
		$basefeeAmountLeft = $order->getBaseHomeserviceAmountInvoiced() - $order->getBaseHomeserviceAmountRefunded();
		if ($basefeeAmountLeft > 0) {
			$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $feeAmountLeft);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basefeeAmountLeft);
			$creditmemo->setHomeserviceAmount($feeAmountLeft);
			$creditmemo->setBaseHomeserviceAmount($basefeeAmountLeft);
		}
		return $this;
    }
    
        
}
