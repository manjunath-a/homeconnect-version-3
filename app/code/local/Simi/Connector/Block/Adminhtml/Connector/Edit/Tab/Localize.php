<?php

/**
 * Magestore
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
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Grid Block
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Block_Adminhtml_Connector_Edit_Tab_Localize extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $store = $this->getRequest()->getParam('store');
        $web = $this->getRequest()->getParam('website');
        $store_name = Mage::helper('connector')->__('All');
        if($store){
            $store_name = Mage::getModel('core/store')->load($store);
        }        
        $web_name = Mage::getModel('core/website')->load($web)->getName();        
        $namefile = $web . DS . $store . DS . 'locale.csv';
        $device_name = Mage::helper('connector')->getNameDeviceById($this->getRequest()->getParam('device_id'));
        $path = Mage::helper('connector')->getDirLocaleCsvByDevice($device_name) . DS . $namefile;
        if (file_exists($path)) {
            $dataObj = new Varien_Object(array(
                        'store_id' => $this->getRequest()->getParam('store'),
                        'file_locale_in_store' => 'locale' . $store . '.csv'
                    ));
        } else {
            $dataObj = new Varien_Object(array(
                        'store_id' => $this->getRequest()->getParam('store'),
                        'file_locale_in_store' => ''
                    ));
        }
        $inStore = $this->getRequest()->getParam('store');
        $defaultLabel = Mage::helper('connector')->__('Use Default');
        $defaultTitle = Mage::helper('connector')->__('-- Please Select --');
        $scopeLabel = Mage::helper('connector')->__('STORE VIEW');
        $data = $dataObj->getData();
        $fieldset = $form->addFieldset('connector_loacle', array('legend' => Mage::helper('connector')->__('Localize information')));
        if (file_exists($path)) {
            $fieldset->addField('file_locale', 'file', array(
                'label' => Mage::helper('connector')->__('Select File to Import'),
                'name' => 'file_locale',
                'disabled' => ($inStore && !$data['file_locale_in_store']),
                'after_element_html' => $inStore ? '<p class="note" id="note_file_locale"><span>'.Mage::helper('connector')->__('Use for '.$store_name.' store in '.$web_name. '.<br/>You can download file that you had been up here.').'
                        <a target="_blank" href="' . Mage::helper('connector')->getFileLocaleCsvByWebsite($web) . '/locale' . $store . '.csv' . '">' . $this->__('Download') . '</a></span></p>
                    </td><td class="use-default">
                                      <input id="name_default" name="name_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($data['file_locale_in_store'] ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
                                      <label for="name_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
                        </td><td class="scope-label">
                                      [' . $scopeLabel . ']
                        ' : '<p class="note" id="note_file_locale"><span> '.Mage::helper('connector')->__('Use for '.$store_name.' store in '.$web_name. '.<br/> You can download file that you had been up here.').' 
                        <a target="_blank" href="' . Mage::helper('connector')->getFileLocaleCsvByWebsite($web) . '/locale' . $store . '.csv' . '">' . $this->__('Download') . '</a></span></p></td><td class="scope-label">
                                      [' . $scopeLabel . ']',
            ));
        } else {
            $fieldset->addField('file_locale', 'file', array(
                'label' => Mage::helper('connector')->__('Select File to Import'),
                'name' => 'file_locale',
                'disabled' => ($inStore && !$data['file_locale_in_store']),
                'after_element_html' => $inStore ? '<p class="note" id="note_file_locale"><span>'.Mage::helper('connector')->__('Use for '.$store_name.' store in '.$web_name).'</p>
                     </td><td class="use-default">
                                      <input id="name_default" name="name_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($data['file_locale_in_store'] ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
                                      <label for="name_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
                        </td><td class="scope-label">
                                      [' . $scopeLabel . ']
                        ' : '<p class="note" id="note_file_locale"><span>'.Mage::helper('connector')->__('Use for '.$store_name.' store in '.$web_name. '. Allow .csv file type').'</p></td><td class="scope-label">
                                      [' . $scopeLabel . ']',
            ));
        }


        $form->setValues($data);
        return parent::_prepareForm();
    }

}