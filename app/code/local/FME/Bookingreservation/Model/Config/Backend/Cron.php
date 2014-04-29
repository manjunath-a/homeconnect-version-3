<?php
/**
 * @author Rao <elegant.rao@gmail.com>
 * will create cron job expression without touching
 * the config.xml file.
 * following core module for logCacheClear
 * */
class FME_Bookingreservation_Model_Config_Backend_Cron extends Mage_Core_Model_Config_Data
{
	const CRON_STRING_PATH  = 'crontab/jobs/fme_bookingreservation_summary/schedule/cron_expr';
	const CRON_MODEL_PATH   = 'crontab/jobs/fme_bookingreservation_summary/run/model';
	
    /**
     * Cron settings after save
     *
     * @return Mage_Adminhtml_Model_System_Config_Backend_Log_Cron
     */
    protected function _afterSave()
    {
        $enabled    = $this->getData('groups/booking_summary/fields/enabled/value');
        $time       = $this->getData('groups/booking_summary/fields/time/value');
        $frequncy   = $this->getData('groups/booking_summary/fields/frequency/value');

        $frequencyDaily     = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_DAILY;
        $frequencyWeekly    = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY;
        $frequencyMonthly   = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_MONTHLY;
 
        if ($enabled)
	{
            $cronDayOfWeek = date('N');
            $cronExprArray = array(
                intval($time[1]),                                   # Minute
                intval($time[0]),                                   # Hour
                ($frequncy == $frequencyMonthly) ? '1' : '*',       # Day of the Month
                '*',                                                # Month of the Year
                ($frequncy == $frequencyWeekly) ? '1' : '*',        # Day of the Week
            );
            $cronExprString = join(' ', $cronExprArray);
        }
        else{
		
            $cronExprString = '';
        }

        try
		{
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();

            Mage::getModel('core/config_data')
                ->load(self::CRON_MODEL_PATH, 'path')
                ->setValue((string) Mage::getConfig()->getNode(self::CRON_MODEL_PATH))
                ->setPath(self::CRON_MODEL_PATH)
                ->save();
        }
        catch (Exception $e)
		{
            Mage::throwException(Mage::helper('adminhtml')->__('Unable to save the cron expression.'));
        }
    }
}