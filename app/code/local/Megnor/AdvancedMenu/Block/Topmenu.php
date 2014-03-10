<?php

if (!Mage::getStoreConfig('advanced_menu/general/enabled') ||
   (Mage::getStoreConfig('advanced_menu/general/ie6_ignore') && Mage::helper('advancedmenu')->isIE6()))
{
class Megnor_AdvancedMenu_Block_Topmenu extends Mage_Page_Block_Html_Topmenu
    {

    }
    return;
}

class Megnor_AdvancedMenu_Block_Topmenu extends Megnor_AdvancedMenu_Block_Navigation
{

}
