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
 * @package 	Magestore_Madapter
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * 
 * 
 * @category 	Magestore
 * @package 	Magestore_Madapter
 * @author  	Magestore Developer
 */
class Simi_Connector_Adminhtml_NoticeController extends Mage_Adminhtml_Controller_Action {

    /**
     * init layout and set active for current menu
     *
     * @return Simi_Connector_Adminhtml_BannerController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('connector/notice')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Notification Manager'), Mage::helper('adminhtml')->__('Notification Manager'));
        return $this;
    }

    /**
     * index action
     */
    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('connector/notice')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('notice_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('connector/notice');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Notice'), Mage::helper('adminhtml')->__('Item Notice'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('connector/adminhtml_notice_edit'))
                    ->_addLeft($this->getLayout()->createBlock('connector/adminhtml_notice_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * save item action
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $resultSend = $this->sendNotice();
            $model = Mage::getModel('connector/notice');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if (!$resultSend) {
                    $this->_redirect('*/*/');
                    return;
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__('Message was successfully sent'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__('Unable to find item to send'));
        $this->_redirect('*/*/');
    }

    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('connector/notice');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message was successfully deleted'));
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
        $bannerIds = $this->getRequest()->getParam('connector');

        if (!is_array($bannerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $notice = Mage::getModel('connector/notice')->load($bannerId);
                    $notice->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannerIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function sendNotice() {
        $data = $this->getRequest()->getParams();
        $trans = $this->send($data);
        return $trans;
    }

    public function send($data) {
        $website = $data['website_id'];
        $collectionDevice = Mage::getModel('connector/device')->getCollection();
        if ((int) $data['device_id'] != 0) {
            $collectionDevice->addFieldToFilter('website_id', array('eq' => $website));
            if ((int) $data['device_id'] == 2) {
                //send android
                $collectionDevice->addFieldToFilter('plaform_id', array('eq' => 3));
                return $this->sendAndroid($collectionDevice, $data);
            } else {
                //send IOS
                $collectionDevice->addFieldToFilter('plaform_id', array('neq' => 3));
                return $this->sendIOS($collectionDevice, $data);
            }
        } else {
            //send all
            $collection = $collectionDevice->addFieldToFilter('website_id', array('eq' => $website));
            $collectionDevice->addFieldToFilter('plaform_id', array('eq' => 3));
            $collection->addFieldToFilter('plaform_id', array('neq' => 3));
            $resultIOS = $this->sendIOS($collection, $data);
            $resultAndroid = $this->sendAndroid($collectionDevice, $data);
            if ($resultIOS || $resultAndroid)
                return true;
            else
                return false;
        }
    }

    public function sendIOS($collectionDevice, $data) {
        $ch = Mage::helper('connector')->getDirPEMfile();
        $dir = Mage::helper('connector')->getDirPEMPassfile();
		$message = $data['notice_content'];

        $body['aps'] = array(
            'alert' => $data['notice_title'],
            'sound' => 'default',
            'badge' => 1,
            'title' => $data['notice_title'],
            'message' => $message,
            'url' => $data['notice_url'],
        );
		$payload = json_encode($body);
		$totalDevice = 0;
		foreach ($collectionDevice as $item) {
			$ctx = stream_context_create();
       	 	stream_context_set_option($ctx, 'ssl', 'local_cert', $ch);
       	 	if ((int) $data['notice_sanbox'] == 1) {
        	    $fp = stream_socket_client('sslv3://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
       	 	} else {
           	 $fp = stream_socket_client('sslv3://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
       	 	}
        	if (!$fp) {
           	 Mage::getSingleton('adminhtml/session')->addError("Failed to connect:" . $err . $errstr . PHP_EOL . "(IOS)");
            	return;
        	}
        
            $deviceToken = $item->getDeviceToken();
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            if (!$result) {
                Mage::getSingleton('adminhtml/session')->addError('Message not delivered (IOS)' . PHP_EOL);
                return false;
            }
			fclose($fp);
			$totalDevice++;
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message successfully delivered to %s devices (IOS)', $totalDevice));
        return true;
    }

    public function sendAndroid($collectionDevice, $data) {
        $api_key = Mage::getStoreConfig('connector/android_key');
//        $api_key = "AIzaSyALAL5f9FOjn2e9s3WkJJvyTvWN9LAyDTs";
        // please enter the registration id of the device on which you want to send the message
        $registrationIDs = array();
        foreach ($collectionDevice as $item) {
            $registrationIDs[] = $item->getDeviceToken();
        }
        $message = array("message" => $data['notice_content'], "url" => $data['notice_url'], "title" => $data['notice_title']);
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => array("message" => $message),
        );

        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json');
        $result = '';
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            
        }
		$re = json_decode($result);
        if ($re->success == 0) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Message not delivered (Android)'));
            return false;
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Message successfully delivered (Android)'));
        return true;
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('connector');
    }

}