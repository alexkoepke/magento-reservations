<?php

class Wage_Customreport_Model_Resource_Product_Sold_Collection extends Mage_Reports_Model_Resource_Product_Sold_Collection
{

    /**
     * Set Date range to collection
     *
     * @param int $from
     * @param int $to
     * @return Mage_Reports_Model_Resource_Product_Sold_Collection
     */
    public function setDateRange($from, $to)
    {
    	$action = Mage::app()->getRequest()->getActionName();
    	if($action == 'reservations'){
    		
            $from = substr($from, 0, 10) ." 00:00";
			$to =  substr($to, 0, 10)." 00:00";
			$time = new DateTime($to);
			$to = $time->modify("-1 second")->format('Y-m-d H:i:s');
			
			
    		$this->_reset()
            ->addAttributeToSelect('*')
            ->modifiedAddOrderedQty($from, $to)
            ->setOrder('ordered_qty', self::SORT_ORDER_DESC);
			
			$this->filterReservationProducts();
			return $this;						
    	}

		$this->_reset()
            ->addAttributeToSelect('*')
            ->addOrderedQty($from, $to)
            ->setOrder('ordered_qty', self::SORT_ORDER_DESC);
			
		$this->filterProductIds();
				
        return $this;
    }
	
	public function modifiedAddOrderedQty($from = '', $to = '')
    {
        $this->getSelect()->reset()
            ->from(
                array('order_items' => $this->getTable('sales_flat_order_item')),
                array(
                    'ordered_qty' => 'sum(order_items.qty_ordered)',
                    'order_items_name' => 'order_items.name',
                    'order_items.product_id'
                ))
            ->group('order_items.product_id')
            ->join(array('custom_item' =>$this->getTable('wage_custom_report_order_item')),
                    'order_items.item_id = custom_item.item_id',
                    array())
            ;
        return $this;
    }
	
	public function setStoreIds($storeIds)
    {
    	$action = Mage::app()->getRequest()->getActionName();
		
    	if($action != 'reservations'){   		
    		return parent::setStoreIds($storeIds);
		}
		
		$storeIds = Mage::getSingleton('core/session')->getCustomReportStoreIds();
		
		if(!$storeIds){
    		return $this;
    	}

		foreach(explode(",",$storeIds) as $id){
			if(!is_numeric($id)){
				return $this;
			}
		} 
		
		return parent::setStoreIds(explode(",",$storeIds));
		
    }
	
	public function filterProductIds()
    {
    	$productIds = Mage::getSingleton('core/session')->getCustomReportProductIds();
		
		if(trim($productIds)==''){
			return $this;
		}

		if($productIds && !is_numeric($productIds)){
			$array = explode(",", $productIds);
			foreach($array as $id){
				if(!is_numeric($id)){
					return $this;
				}
			} 
		}

		$this->getSelect()->where('e.entity_id IN('.$productIds.')');
        return $this;
    }

	public function filterReservationProducts()
    {
    	$productIds = Mage::getSingleton('core/session')->getCustomReportProductIds();
		
		if(trim($productIds)==''){
			return $this;
		}

		if($productIds && !is_numeric($productIds)){
			$array = explode(",", $productIds);
			foreach($array as $id){
				if(!is_numeric($id)){
					return $this;
				}
			} 
		}

		$this->getSelect()->where('order_items.product_id IN('.$productIds.')');
        return $this;
    }

}
