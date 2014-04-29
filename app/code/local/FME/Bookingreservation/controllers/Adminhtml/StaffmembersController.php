<?php

class FME_Bookingreservation_Adminhtml_StaffmembersController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('bookingreservation/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Staff Members Manager'), Mage::helper('adminhtml')->__('Staff Members Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		
		$this->_initAction()
		->renderLayout();
	}



	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('bookingreservation/staffmembers')->load($id);

		if ($model->getId() || $id == 0) {
			
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			
			
			
			if (!empty($data)) {
				$model->setData($data);
			}else{
				
				$data = $model->getData();
				if (!empty($data)) {
					$model->setData($data);
				}
				
			}			

			Mage::register('staffmembers_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('bookingreservation/staffmembers');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Staff Member Manager'), Mage::helper('adminhtml')->__('Staff Member Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Staff Member News'), Mage::helper('adminhtml')->__('Staff Member News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_edit'))
				->_addLeft($this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bookingreservation')->__('Staff Members does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		
		if ($data = $this->getRequest()->getPost()) {
			
			try {
				
				
				if(isset($_FILES['member_pic']['name']) && $_FILES['member_pic']['name'] != '') {
					try {	
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('member_pic');
						
						// Any extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(false);
						
						// Set the file upload mode 
						// false -> get the file directly in the specified folder
						// true -> get the file in the product like folders 
						//	(file.jpg will go in something like /media/f/i/file.jpg)
						$uploader->setFilesDispersion(true);
								
						// We set media as the upload dir
						$path = Mage::getBaseDir('media') . DS .'bookingreservation' . DS . 'staffmembers' . DS;
						
						$result = $uploader->save($path, $_FILES['member_pic']['name'] );						
						
						//echo "<pre>"; print_r($result['file']); exit;
						$data['member_pic'] = Mage::getBaseUrl('media') .'bookingreservation' . DS . 'staffmembers'.$result['file'];
						
						
									
						
					} catch (Exception $e) {
			      
					}
				
					
				}else{
					
					$data['member_pic'] = $data['member_pic']['value'];
					
				}
				
				
				
				$model = Mage::getModel('bookingreservation/staffmembers');		
				$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
				
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}
				
				
				
				$model->save();				
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bookingreservation')->__('Member was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bookingreservation')->__('Unable to find Member to save'));
        $this->_redirect('*/*/');
	}
	
	
	
	
	//Member booking grid	
	
	protected function _initMemberBooking()
	{
		
		$memberId  = (int) $this->getRequest()->getParam('id');
			
		if (!$memberId) {
			Mage::register('current_member_bookings', -1);
			return false;
		}
		
		
		Mage::register('current_member_bookings', $memberId);	
			
	}
	
	
	public function memberbookingsAction()
	{
		
		$this->_initMemberBooking();
		
		$this->loadLayout();
		$this->getLayout()->getBlock('staffmembers.edit.tab.memberbookings');
		
		$this->renderLayout();
		
	}		
	
	//Member booking grid
	
	
	
	
	
	
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('bookingreservation/staffmembers');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Staff Member was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $bookingreservationIds = $this->getRequest()->getParam('staffmembers');
        if(!is_array($bookingreservationIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($bookingreservationIds as $bookingreservationId) {
                    $bookingreservation = Mage::getModel('bookingreservation/staffmembers')->load($bookingreservationId);
                    $bookingreservation->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($bookingreservationIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $bookingreservationIds = $this->getRequest()->getParam('staffmembers');
        if(!is_array($bookingreservationIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($bookingreservationIds as $bookingreservationId) {
                    $bookingreservation = Mage::getSingleton('bookingreservation/staffmembers')
                        ->load($bookingreservationId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($bookingreservationIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'staffmembers.csv';
        $content    = $this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'staffmembers.xml';
        $content    = $this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
    
    
    //csv and xml export for member bookings Grid
    
    public function exportMemberBookingsCsvAction()
    {
	$memberId  = (int) $this->getRequest()->getParam('id');
	Mage::register('current_member_bookings', $memberId);
	
        $fileName   = 'memberbookings.csv';
        $content    = $this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_edit_tab_memberbookings')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportMemberBookingsXmlAction()
    {
	$memberId  = (int) $this->getRequest()->getParam('id');
	Mage::register('current_member_bookings', $memberId);
	
        $fileName   = 'memberbookings.xml';
        $content    = $this->getLayout()->createBlock('bookingreservation/adminhtml_staffmembers_edit_tab_memberbookings')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
    //-----------------------------------------------
    

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
