<?php
class Wage_Customreport_Block_Report_Product_Sold_Grid extends Mage_Adminhtml_Block_Report_Grid
{

	protected function _prepareLayout()
    {
        parent::_prepareLayout();
		//$this->getChild('store_switcher')->setTemplate('customreport/store/switcher.phtml');
        return $this;
    }
	
	protected function _prepareCollection()
    {
        parent::_prepareCollection();
        Mage::getSingleton('core/session')->setCustomReportProductIds('');
        $filterProducts = $this->getFilter('filter_products');
        if($filterProducts !=""){
            Mage::getSingleton('core/session')->setCustomReportProductIds($filterProducts);
        }
        $this->getCollection()->initReport('reservation/reservationitem');

        //$this->getCollection();
        /*$filterProducts = $this->getFilter('filter_products');
        $filterType = $this->getFilter('show_type');
        Mage::getSingleton('core/session')->setCustomReportProductIds('');
        Mage::getSingleton('core/session')->setCustomReportFilterType(0);

        if($filterProducts !=""){
            Mage::getSingleton('core/session')->setCustomReportProductIds($filterProducts);
            //$this->_model->filterProducts($filterProducts);

            //getCollection()
            //->addFieldToFilter(array(
            //array('field' => 'product_id','in' => array(34))));
        }

        if($filterType > 0){
            Mage::getSingleton('core/session')->setCustomReportFilterType($filterType);
        }

        //Mage::log('_prepareCollection filter products:'. $filterProducts);
        //Mage::log('_prepareCollection filter type:'. $filterType);*/
    }

    /**
     * Prepare Grid columns
     *
     * @return Mage_Adminhtml_Block_Report_Product_Sold_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('product_name', array(
            'header'    =>Mage::helper('reports')->__('Product Name'),
            'index'     =>'product_name'
        ));

        $this->addColumn('total_qty', array(
            'header'    =>Mage::helper('reports')->__('Quantity Ordered'),
            'width'     =>'120px',
            'align'     =>'right',
            'index'     =>'total_qty',
            'total'     =>'sum',
            'type'      =>'number'
        ));

        $this->addExportType('*/*/exportSoldCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportSoldExcel', Mage::helper('reports')->__('Excel XML'));

        return $this;
    }
}
