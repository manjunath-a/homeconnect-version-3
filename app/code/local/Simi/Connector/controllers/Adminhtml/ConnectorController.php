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
 * Connector Adminhtml Controller
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Adminhtml_ConnectorController extends Mage_Adminhtml_Controller_Action {

    /**
     * init layout and set active for current menu
     *
     * @return Simi_Connector_Adminhtml_ConnectorController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('connector/connector')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    /**
     * index action
     */
    public function indexAction() {
        $websiteId = Mage::getBlockSingleton('connector/adminhtml_web_switcher')->getWebsiteId();
        $keyItem = Mage::getModel('connector/key')->getKey($websiteId);
        if (count($keyItem->getData())) {
            $app_list = Mage::getModel('connector/simicart_api')->getListApp($keyItem->getKeySecret());
            if ($app_list->status == "FAIL") {
                Mage::getModel('connector/plugin')->deleteList($websiteId);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__('Authorize secret key is incorrect'));
            } else {
                Mage::getModel('connector/app')->saveList($app_list, $websiteId);
                Mage::getModel('connector/plugin')->deleteList($websiteId);
                Mage::getModel('connector/plugin')->saveList($app_list, $websiteId);
            }
        }
        $this->_initAction()
                ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction() {
        $this->loadLayout();
        $this->_setActiveMenu('connector/connector');
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * save item action
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $device_id = $this->getRequest()->getParam('device_id');
            $store_id = $this->getRequest()->getParam('store');
            $web_id = $this->getRequest()->getParam('website');
            $categories = implode(',', array_unique(explode(',', $data['category_ids'])));
            Mage::getModel('connector/app')->saveCategories($web_id, $categories);
            try {
                if (isset($_FILES['pem_file']['name']) && $_FILES['pem_file']['name'] != '') {
                    $this->_helper()->savePem($device_id, $_FILES['pem_file']);
                }
                //for send notification android
                if (isset($data['android_key']) && $data['android_key']) {
                    Mage::getConfig()->saveConfig('connector/android_key', $data['android_key']);
                    Mage::getConfig()->saveConfig('connector/android_sendid', $data['android_sendid']);
                    Mage::getConfig()->saveCache();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'),
                        'store' => $this->getRequest()->getParam('store'),
                        'device_id' => $this->getRequest()->getParam('device_id'),
                        'website' => $this->getRequest()->getParam('website')));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'store' => $this->getRequest()->getParam('store'),
                    'device_id' => $this->getRequest()->getParam('device_id'),
                    'website' => $this->getRequest()->getParam('website')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('connector/connector');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction() {
        $connectorIds = $this->getRequest()->getParam('connector');
        if (!is_array($connectorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($connectorIds as $connectorId) {
                    $connector = Mage::getModel('connector/connector')->load($connectorId);
                    $connector->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($connectorIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction() {
        $connectorIds = $this->getRequest()->getParam('connector');
        if (!is_array($connectorIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($connectorIds as $connectorId) {
                    $connector = Mage::getSingleton('connector/connector')
                            ->load($connectorId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($connectorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function pluginAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('connector.edit.tab.plugin');
        $this->renderLayout();
    }

    public function pluginGridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('connector.edit.tab.plugin');
        $this->renderLayout();
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction() {
        $fileName = 'connector.csv';
        $content = $this->getLayout()->createBlock('connector/adminhtml_connector_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction() {
        $fileName = 'connector.xml';
        $content = $this->getLayout()->createBlock('connector/adminhtml_connector_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('connector');
    }

    protected function _helper() {
        return Mage::helper('connector');
    }

    // add category 21-4-2014
    protected function _initItem() {
        if (!Mage::registry('simi_categories')) {
            if ($this->getRequest()->getParam('id')) {
                Mage::register('simi_categories', Mage::getModel('connector/app')->load($this->getRequest()->getParam('id'))->getCategories());
            }
        }
    }

    public function categoriesAction() {
        $this->_initItem();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('connector/adminhtml_connector_edit_tab_categories')->toHtml()
        );
    }

    public function categoriesJsonAction() {
        $this->_initItem();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('connector/adminhtml_connector_edit_tab_categories')
                        ->getCategoryChildrenJson($this->getRequest()->getParam('category')));
    }

    //end 
    // add save key 962014

    public function saveKeyAction() {
        if ($data = $this->getRequest()->getPost()) {
            $key = $data["key_app"];
            $websiteId = $data["website_id"];
            try {
                $app_list = Mage::getModel('connector/simicart_api')->getListApp($key);
                // Zend_debug::dump($websiteId);die();
                if ($app_list->status == "FAIL") {
                    Mage::getModel('connector/app')->deleteList($websiteId);
                    Mage::getModel('connector/plugin')->deleteList($websiteId);
                    Mage::getModel('connector/key')->setKey($key, $websiteId);
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__('Authorize secret key is incorrect'));
                } else {
                    Mage::getModel('connector/app')->saveList($app_list, $websiteId);
                    Mage::getModel('connector/plugin')->deleteList($websiteId);
                    Mage::getModel('connector/plugin')->saveList($app_list, $websiteId);
                    Mage::getModel('connector/key')->setKey($key, $websiteId);
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__('Authorize secret key is correct'));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    //end add
}