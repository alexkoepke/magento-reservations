<?php

class Wage_Customimport_Block_Report_Grid extends Mage_Adminhtml_Block_Report_Grid
{		
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
		$this->getChild('store_switcher')->setTemplate('customreport/store/switcher/enhanced.phtml');
        return $this;
    }

}
	