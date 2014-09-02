<?php

class Megnor_AdvancedMenu_Block_Toggle extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        if (!Mage::getStoreConfig('advanced_menu/general/enabled')) return;
        if (Mage::getStoreConfig('advanced_menu/general/ie6_ignore') && Mage::helper('advancedmenu')->isIE6()) return;
        $layout = $this->getLayout();
        $topnav = $layout->getBlock('catalog.topnav');
        if (is_object($topnav))
        {
            $topnav->setTemplate('advancedmenu/top.phtml');
            $head = $layout->getBlock('head'); 
        }
    }
}
