<?php

class Wage_Reservation_Model_Mysql4_Reservation extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('reservation/reservation_order', 'reservation_id');
    }
}