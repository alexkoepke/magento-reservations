<?php

class Wage_Reservation_Model_Mysql4_Reservationitem_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reservation/reservationitem');
    }

    public function setStoreIds($storeIds)
    {
        return $this;
    }
}