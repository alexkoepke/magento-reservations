<?php

class Wage_Reservation_Model_Reservationitem extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reservation/reservationitem');
    }

}