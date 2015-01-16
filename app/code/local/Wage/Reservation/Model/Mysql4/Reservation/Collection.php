<?php

class Wage_Reservation_Model_Mysql4_Reservation_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reservation/reservation');
    }
}