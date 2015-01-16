<?php

class Wage_Reservation_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getExcludeDates()
    {
        $congigData = Mage::getStoreConfig('reservation/general/exclude_dates');
        $excludeDates = unserialize($congigData);
        $notAvailableDates = array();
        foreach ($excludeDates as $excludeDate) {
            $notAvailableDates[] = $excludeDate['excludedate'];
        }
        return $notAvailableDates;
    }
    public function getMasterDates()
    {
        $configData = Mage::getStoreConfig('reservation/general/master_dates');
        $includeDates = unserialize($configData);

        $availableDates = array();
        foreach ($includeDates as $includeDate) {
            $availableDates[] = $includeDate['include_date'];
        }

        return $availableDates;
    }
    public function getScheduleDates()
    {
        $congigData = Mage::getStoreConfig('reservation/schedules/daterange');

        $schedules = unserialize($congigData);

        $array = array();
        foreach ($schedules as $schedule) {
            $array[] = $schedule['schedule_start'];
            $array[] = $schedule['schedule_end'];
        }
        return $array;
    }
}
