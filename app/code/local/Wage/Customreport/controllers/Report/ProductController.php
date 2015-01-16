<?php

 require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Report'.DS.'ProductController.php');
class Wage_Customreport_Report_ProductController extends Mage_Adminhtml_Report_ProductController
{
	
	public function reservationsAction()
    {
        $this->_title($this->__('Reports'))
             ->_title($this->__('Online Reservations'));
		$storeIds = $this->getRequest()->getParam('store');
		Mage::getSingleton('core/session')->setCustomReportStoreIds($storeIds);
        $this->_initAction()
            ->_setActiveMenu('report/product/reservations')
            ->_addBreadcrumb(Mage::helper('reports')->__('Online Reservations'), Mage::helper('reports')->__('Online Reservations'))
            ->_addContent($this->getLayout()->createBlock('customreport/report_product_reservations'))
            ->renderLayout();
    }

    /**
     * Check is allowed for report
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'viewed':
                return Mage::getSingleton('admin/session')->isAllowed('report/products/viewed');
                break;
            case 'sold':
                return Mage::getSingleton('admin/session')->isAllowed('report/products/sold');
                break;
			 case 'reservations':
                return Mage::getSingleton('admin/session')->isAllowed('report/customreport');
                break;
            case 'lowstock':
                return Mage::getSingleton('admin/session')->isAllowed('report/products/lowstock');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('report/products');
                break;
        }
    }

    /**
     * Export Sold Products report to CSV format action
     *
     */
    public function exportSoldCsvAction()
    {
        $fileName   = 'reserve_products_ordered.csv';
        $content    = $this->getLayout()
            ->createBlock('customreport/report_product_sold_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export Sold Products report to XML format action
     *
     */
    public function exportSoldExcelAction()
    {
        $fileName   = 'reserve_products_ordered.xml';
        $content    = $this->getLayout()
            ->createBlock('customreport/report_product_sold_grid')
            ->getExcel($fileName);

        $this->_prepareDownloadResponse($fileName, $content);
    }
}
