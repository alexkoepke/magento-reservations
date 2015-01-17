<?php

class Wage_Customreport_Block_Report_Product_Reservations extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	
    /**
     * Initialize container block settings
     *
     */
    public function __construct()
    {
        $this->_controller = 'report_product_sold';
        $this->_headerText = Mage::helper('reports')->__('Online Reservations');
        parent::__construct();
        $this->_removeButton('add');
    }
	
}
