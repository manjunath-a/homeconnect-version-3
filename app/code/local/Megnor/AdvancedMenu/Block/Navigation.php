<?php

class Megnor_AdvancedMenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{
    const CUSTOM_BLOCK_TEMPLATE = "tm_advanced_menu_%d";

    private $_productsCount = null;

    public function showHomeLink()
    {
        return Mage::getStoreConfig('advanced_menu/general/show_home_link');
    }
	
	public function isActivecustomBlock() {					
		return Mage::getStoreConfig('advanced_menu/customblock/displaymenucustomblock');		
    }
	
	public function getcustomemenuidentifier() {					
		return Mage::getStoreConfig('advanced_menu/customblock/customblock_identifier');		
    }

    public function drawAdvancedMenuItem($category, $level = 0, $last = false)
    {
        if (!$category->getIsActive()) return '';

        $html = array();

        $id = $category->getId();
        // --- Static Block ---
        $blockId = sprintf(self::CUSTOM_BLOCK_TEMPLATE, $id); // --- static block key
        #Mage::log($blockId);
        $collection = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('identifier', array('like' => $blockId . '%'))->addFieldToFilter('is_active', 1);
        $blockId = $collection->getFirstItem()->getIdentifier();
        #Mage::log($blockId);
        $blockHtml = $this->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        // --- Sub Categories ---
        $activeChildren = $this->_getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        // --- Popup functions for show ---
        $drawPopup = ($blockHtml || count($activeChildren));
        if ($drawPopup)
        {
            $html[] = '<div id="menu' . $id . '" class="menu' . $active . ' parent-arrow" onmouseover="megnorShowMenuPopup(this, \'popup' . $id . '\');" onmouseout="megnorHideMenuPopup(this, event, \'popup' . $id . '\', \'menu' . $id . '\')">';
        }
        else
        {
            $html[] = '<div id="menu' . $id . '" class="menu' . $active . '">';
        }
        // --- Top Menu Item ---
        $html[] = '<div class="parentMenu arrow">';
        $html[] = '<a href="'.$this->getCategoryUrl($category).'">';
        $name = $this->escapeHtml($category->getName());
        if (Mage::getStoreConfig('advanced_menu/general/non_breaking_space'))
            $name = str_replace(' ', '&nbsp;', $name);
        $html[] = '<span>' . $name . '</span>';
        $html[] = '</a>';
        $html[] = '</div>';
        $html[] = '</div>';
        // --- Add Popup block (hidden) ---
        if ($drawPopup)
        {
            // --- Popup function for hide ---
            $html[] = '<div id="popup' . $id . '" class="megnor-advanced-menu-popup" onmouseout="megnorHideMenuPopup(this, event, \'popup' . $id . '\', \'menu' . $id . '\')" onmouseover="megnorPopupOver(this, event, \'popup' . $id . '\', \'menu' . $id . '\')">';
			$html[] = '<div class="megnor-advanced-menu-popup_inner">';
            // --- draw Sub Categories ---
			
			
			// ---------- Set static block for TOP POSITION --------- 
			if (Mage::getStoreConfig('advanced_menu/general/block_position') == "top")
			{
				// --- draw Custom User Block ---			
				if ($blockHtml)
				{
					$html[] = '<div id="' . $blockId . '" class="block2">';
					$html[] = $blockHtml;
					$html[] = '</div>';
				}		
				if (count($activeChildren))
				{
					$html[] = '<div class="block1">';
					$html[] = $this->drawColumns($activeChildren);
					$html[] = '</div>';
				}
            }
			
			// ---------- Set static block for BOTTOM POSITION --------- 			
			if (Mage::getStoreConfig('advanced_menu/general/block_position') == "bottom")
			{	
				if (count($activeChildren))
				{
					$html[] = '<div class="block1">';
					$html[] = $this->drawColumns($activeChildren);
					$html[] = '</div>';
				}
				$html[] = '<div class="clearBoth"></div>';
				// --- draw Custom User Block ---			
				if ($blockHtml)
				{
					$html[] = '<div id="' . $blockId . '" class="block2">';
					$html[] = $blockHtml;
					$html[] = '</div>';
				}	
            }
			
			// ---------- Set static block for LEFT POSITION --------- 			
			if (Mage::getStoreConfig('advanced_menu/general/block_position') == "left")
			{	
				// --- draw Custom User Block ---			
				if ($blockHtml)
				{
					$html[] = '<div id="' . $blockId . '" class="block2" style="float:left">';
					$html[] = $blockHtml;
					$html[] = '</div>';
				}
				if (count($activeChildren))
				{
					$html[] = '<div class="block1" style="float:left">';
					$html[] = $this->drawColumns($activeChildren);
					$html[] = '</div>';
				}					
            }
			
			// ---------- Set static block for RIGHT POSITION --------- 			
			if (Mage::getStoreConfig('advanced_menu/general/block_position') == "right")
			{					
				if (count($activeChildren))
				{
					$html[] = '<div class="block1" style="float:left">';
					$html[] = $this->drawColumns($activeChildren);
					$html[] = '</div>';
				}					
				// --- draw Custom User Block ---			
				if ($blockHtml)
				{
					$html[] = '<div id="' . $blockId . '" class="block2" style="float:left">';
					$html[] = $blockHtml;
					$html[] = '</div>';
				}
            }
			
            
			
            $html[] = '</div>';
			 $html[] = '</div>';
        }

        $html = implode("\n", $html);
        return $html;
    }

    public function drawMenuItem($children, $level = 1)
    {
        $html = '<div class="itemMenu level' . $level . '">';
        $keyCurrent = $this->getCurrentCategory()->getId();
        foreach ($children as $child)
        {
            if ($child->getIsActive())
            {
                // --- class for active category ---
                $active = '';
                if ($this->isCategoryActive($child))
                {
                    $active = ' actParent';
                    if ($child->getId() == $keyCurrent) $active = ' act';
                }
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                if (Mage::getStoreConfig('advanced_menu/general/non_breaking_space'))
                    $name = str_replace(' ', '&nbsp;', $name);
                $html.= '<a class="itemMenuName level' . $level . $active . '" href="' . $this->getCategoryUrl($child) . '"><span>' . $name . '</span></a>';
                $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0)
                {
                    $html.= '<div class="itemSubMenu level' . $level . '">';
                    $html.= $this->drawMenuItem($activeChildren, $level + 1);
                    $html.= '</div>';
                }
            }
        }
        $html.= '</div>';
        return $html;
    }

    public function drawColumns($children)
    {
        $html = '';
        // --- explode by columns ---
		
        $columns = (int)Mage::getStoreConfig('advanced_menu/columns/count');
        if ($columns < 1) $columns = 1;
        $chunks = $this->_explodeByColumns($children, $columns);
        // --- draw columns ---
        $lastColumnNumber = count($chunks);
        $i = 1;
		
        foreach ($chunks as $key => $value)
        {
            if (!count($value)) continue;
            $class = '';			
            if ($i == 1) $class.= ' first';
            if ($i == $lastColumnNumber) $class.= ' last';
            if ($i % 2) $class.= ' odd'; else $class.= ' even';			
            $html.= '<div class="column' . $class . '">';
            $html.= $this->drawMenuItem($value, 1);
            $html.= '</div>';		
			if($i==$columns){ $html.= '<div class="clearBoth"></div>'; $i=0;}	
            $i++;
        }
        return $html;
    }

    protected function _getActiveChildren($parent, $level)
    {
        $activeChildren = array();
        // --- check level ---
        $maxLevel = (int)Mage::getStoreConfig('advanced_menu/general/max_level');
        if ($maxLevel > 0)
        {
            if ($level >= ($maxLevel - 1)) return $activeChildren;
        }
        // --- / check level ---
        if (Mage::helper('catalog/category_flat')->isEnabled())
        {
            $children = $parent->getChildrenNodes();
            $childrenCount = count($children);
        }
        else
        {
            $children = $parent->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        if ($hasChildren)
        {
            foreach ($children as $child)
            {
                if ($this->_isCategoryDisplayed($child))
                {
                    array_push($activeChildren, $child);
                }
            }
        }
        return $activeChildren;
    }

    private function _isCategoryDisplayed(&$child)
    {
        if (!$child->getIsActive()) return false;
        // === check products count ===
        // --- get collection info ---
        if (!Mage::getStoreConfig('advanced_menu/general/display_empty_categories'))
        {
            $data = $this->_getProductsCountData();
            // --- check by id ---
            $id = $child->getId();
            #Mage::log($id); Mage::log($data);
            if (!isset($data[$id]) || !$data[$id]['product_count']) return false;
        }
        // === / check products count ===
        return true;
    }

    private function _getProductsCountData()
    {
        if (is_null($this->_productsCount))
        {
            $collection = Mage::getModel('catalog/category')->getCollection();
            $storeId = Mage::app()->getStore()->getId();
            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('is_active')
                ->setProductStoreId($storeId)
                ->setLoadProductCount(true)
                ->setStoreId($storeId);
            $productsCount = array();
            foreach($collection as $cat)
            {
                $productsCount[$cat->getId()] = array(
                    'name' => $cat->getName(),
                    'product_count' => $cat->getProductCount(),
                );
            }
            #Mage::log($productsCount);
            $this->_productsCount = $productsCount;
        }
        return $this->_productsCount;
    }

    private function _explodeByColumns($target, $num)
    {
        $count = count($target);
        if ($count) $target = array_chunk($target, 1);
        $target = array_pad($target, $num, array());
        #return $target;
        if ((int)Mage::getStoreConfig('advanced_menu/columns/integrate') && count($target))
        {
            // --- combine consistently numerically small column ---
            // --- 1. calc length of each column ---
            $max = 0; $columnsLength = array();
            foreach ($target as $key => $child)
            {
                $count = 0;
                $this->_countChild($child, 1, $count);
                if ($max < $count) $max = $count;
                $columnsLength[$key] = $count;
            }
            // --- 2. merge small columns with next ---
            $xColumns = array(); $column = array(); $cnt = 0;
            $xColumnsLength = array(); $k = 0;
            foreach ($columnsLength as $key => $count)
            {
                $cnt+= $count;
                if ($cnt > $max && count($column))
                {
                    $xColumns[$k] = $column;
                    $xColumnsLength[$k] = $cnt - $count;
                    $k++; $column = array(); $cnt = $count;
                }
                $column = array_merge($column, $target[$key]);
            }
            $xColumns[$k] = $column;
            $xColumnsLength[$k] = $cnt - $count;
            // --- 3. integrate columns of one element ---
            $target = $xColumns; $xColumns = array(); $nextKey = -1;
            if ($max > 1 && count($target) > 1)
            {
                foreach($target as $key => $column)
                {
                    if ($key == $nextKey) continue;
                    if ($xColumnsLength[$key] == 1)
                    {
                        // --- merge with next column ---
                        $nextKey = $key + 1;
                        if (isset($target[$nextKey]) && count($target[$nextKey]))
                        {
                            $xColumns[] = array_merge($column, $target[$nextKey]);
                            continue;
                        }
                    }
                    $xColumns[] = $column;
                }
                $target = $xColumns;
            }
        }
        return $target;
    }

    private function _countChild($children, $level, &$count)
    {
        foreach ($children as $child)
        {
            if ($child->getIsActive())
            {
                $count++; $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) $this->_countChild($activeChildren, $level + 1, $count);
            }
        }
    }
}
