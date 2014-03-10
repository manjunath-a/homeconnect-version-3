<?php

class Megnor_Manufacturer_Block_Adminhtml_Manufacturer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('manufacturer_form', array('legend'=>Mage::helper('manufacturer')->__('Manufacturer information')));
        //get Manufecturer's Attribut options
        //DebugBreak();
		
		//Mage::getStoreConfig('manufacturer/general/attribute_code')
		
        $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', Mage::getStoreConfig('manufacturer/general/attribute_code'));
        $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
        $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
        $attributeOptions = $attribute_table->getAllOptions(false);
        $default=array('value'=>'','label'=>'Choose Brand');
        $i=0;
        $manufacturer[$i]=$default;
        foreach($attributeOptions as $key=>$value){
            $i++;
            $manufacturer[$i]=$value;
        }
        //End
        $fieldset->addField('manufacturer_name', 'select', array(
        'label'     => Mage::helper('manufacturer')->__('Select Manufacturer'),
        'class'     => 'required-entry',
        'required'  => true,
        'name'      => 'manufacturer_name',
        'values'    =>$manufacturer,
        ));
        
		$fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('manufacturer')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
		$fieldset->addField('urlkey', 'text', array(
            'label'     => Mage::helper('manufacturer')->__('URL Key'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'urlkey',
            //'value'     => $_model->getLabel()
        ));

		$fieldset->addField('filename', 'image', array(
            'label'     => Mage::helper('manufacturer')->__('Image'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'filename',
        ));
		
        
        //At the time of insert of manufacturer the image uploaded is reqired
        //While time of Edit the control can be blank
  /*      if(Mage::registry('manufacturer_data')->getData('filename')!=""){
            $fieldset->addField('filename', 'file', array(
            'label'     => Mage::helper('manufacturer/data')->__('Brand Logo'),
            'required'  => false,
            'name'      => 'filename',
            'after_element_html' => Mage::registry('manufacturer_data')->getData('filename') != "" ? '<span class="hint"><img src="'.Mage::getBaseUrl('media')."Manufacturer/".Mage::registry('manufacturer_data')->getData('filename').'" width="25" height="25" name="manufacturer_image" style="vertical-align: middle;" /></span>':'',
            ));
        }else{
            $fieldset->addField('filename', 'file', array(
            'label'     => Mage::helper('manufacturer/data')->__('Brand Logo'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'filename',
            'after_element_html' => Mage::registry('manufacturer_data')->getData('filename') != "" ? '<span class="hint"><img src="'.Mage::getBaseUrl('media')."Manufacturer/".Mage::registry('manufacturer_data')->getData('filename').'" width="25" height="25" name="manufacturer_image" style="vertical-align: middle;" /></span>':'',
            ));
        }*/
        //<br/><input type="checkbox" name="image_chk" id="image_chk"/><label for="image_chk"> Delete Image</label>

/*        $fieldset->addField('status', 'select', array(
        'label'     => Mage::helper('manufacturer')->__('Status'),
        'name'      => 'status',
        'values'    => array(
        array(
        'value'     => 1,
        'label'     => Mage::helper('manufacturer')->__('Enabled'),
        ),

        array(
        'value'     => 2,
        'label'     => Mage::helper('manufacturer')->__('Disabled'),
        ),
        ),
        ));*/


		$fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('manufacturer')->__('Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'description',
        	'title'     => Mage::helper('manufacturer')->__('Description'),
			'style'     => 'width:600px;height:200px;',
            'wysiwyg'   => false,
			//'value'     => $_model->getDescription()

        ));


		
		$fieldset->addField('meta_keywords', 'editor', array(
			'label'     => Mage::helper('manufacturer')->__('Meta Keywords'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_keywords',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false,
			//'value'     => $_model->getDescription()
			 'config'    =>  $config,
		));
		$fieldset->addField('meta_description', 'editor', array(
			'label'     => Mage::helper('manufacturer')->__('Meta Description'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_description',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false,
			//'value'     => $_model->getDescription()
			 'config'    =>  $config,
		));
		


		$fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('manufacturer')->__('Is Active'),
            'name'      => 'status',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            //'value'     => $_model->getIsActive()
        ));
		$fieldset->addField('position', 'text', array(
            'label'     => Mage::helper('manufacturer')->__('Position'),
            'required'  => false,
            'name'      => 'position',
        ));
				



        if ( Mage::getSingleton('adminhtml/session')->getManufacturerData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getManufacturerData());
            Mage::getSingleton('adminhtml/session')->setManufacturerData(null);
        } elseif ( Mage::registry('manufacturer_data') ) {
            $form->setValues(Mage::registry('manufacturer_data')->getData());
        }
        return parent::_prepareForm();
    }
}