<?php


class Megnor_Manufacturer_Helper_Manufacturer extends Mage_Core_Helper_Abstract
{
    /**
    * Renders page
    *
    * Call from controller action
    *
    * @param Mage_Core_Controller_Front_Action $action
    * @param integer $pageId
    * @return boolean
    */
    public function renderPage(Mage_Core_Controller_Front_Action $action, $id=null)
    {
        $model = Mage::getSingleton('manufacturer/manufacturer');
        if (!is_null($id) && $id!==$model->getId()) {
            if (!$model->load($id)) {
                return false;
            }
        }

        if (!$model->getId() OR $model->getStatus() != 1) {
            return false;
        }

        //print_r($page->getData());exit;

        /*if ($page->getCustomTheme()) {
            $apply = true;
            $today = Mage::app()->getLocale()->date()->toValue();
            if (($from = $page->getCustomThemeFrom()) && strtotime($from)>$today) {
                $apply = false;
            }
            if ($apply && ($to = $page->getCustomThemeTo()) && strtotime($to)<$today) {
                $apply = false;
            }
            if ($apply) {
                list($package, $theme) = explode('/', $page->getCustomTheme());
                Mage::getSingleton('core/design_package')
                    ->setPackageName($package)
                    ->setTheme($theme);
            }
        }*/

        $action->loadLayout(array('default', 'manufacturer_view'), false, false);
        $action->getLayout()->getUpdate()->addUpdate($model->getLayoutUpdateXml());
        $action->generateLayoutXml()->generateLayoutBlocks();

        if ($storage = Mage::getSingleton('catalog/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }

        if ($storage = Mage::getSingleton('checkout/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
        $action->renderLayout();

        return true;
    }
	
	
}