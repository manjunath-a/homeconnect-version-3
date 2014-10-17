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
 * @package 	Magestore_Popup
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Popup Edit Form Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Popup
 * @author  	Magestore Developer
 */
class Simi_Popup_Block_Adminhtml_Popup_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * prepare form's information for block
     *
     * @return Simi_Popup_Block_Adminhtml_Popup_Edit_Form
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form(array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', array(
                        'id' => $this->getRequest()->getParam('id'),
                    )),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}