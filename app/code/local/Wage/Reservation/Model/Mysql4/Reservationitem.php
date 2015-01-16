<?php

class Wage_Reservation_Model_Mysql4_Reservationitem extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('reservation/reservation_order_item', 'id');
    }

    public function setDateRange($from, $to)
    {
        $from = substr($from, 0, 10) ." 00:00";
        $to =  substr($to, 0, 10)." 00:00";
        $time = new DateTime($to);
        $to = $time->modify("-1 second")->format('Y-m-d H:i:s');

        $reservation = Mage::getModel('reservation/reservationitem')->getCollection();

        $reservation->join('reservation_order', 'reservation_order.reservation_id = main_table.reservation_id',
            array('store_id'=>'store_id', 'created_date' => 'created_date'));

        $storeIds = Mage::getSingleton('core/session')->getCustomReportStoreIds();

        if ($storeIds) {
            $reservation->getSelect()->where('reservation_order.store_id IN (?)', (array)$storeIds);
        }

        $reservation->getSelect()->where("reservation_order.reserve_date BETWEEN '$from' AND '$to'");

        $productIds = Mage::getSingleton('core/session')->getCustomReportProductIds();

        if ($productIds) {
            $reservation->getSelect()->where('main_table.product_id IN('.$productIds.')');
        }

        $reservation->getSelect()->columns('SUM(main_table.ordered_qty) as total_qty');
        $reservation->getSelect()->group('main_table.product_id');

        return $reservation;

    }
}
