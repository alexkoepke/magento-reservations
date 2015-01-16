<?php
class Wage_Reservation_Model_Adminhtml_System_Config_Source_Storetype 
{
     public function toOptionArray()
   {
       $storetypes = array(
           array('value' => 'main', 'label' => 'Main'),
           array('value' => 'franchise', 'label' => 'Franchise'),
           array('value' => 'corporate', 'label' => 'Corporate'),
           array('value' => 'express', 'label' => 'Express'),
       );
 
       return $storetypes;
   }
}
