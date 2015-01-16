<?php

class Wage_Reservation_Block_Email_Item extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('reservation/email/item.phtml');
    }

    /*public function getItems($postObject)
    {
        $items = array();
        if ($hams = $postObject->getHam()) {
            foreach ($hams as $id => $qty) {
                if ($qty > 0) {
                    $product = Mage::getModel('catalog/product')->load($id);
                    $items[$id] = array(
                        'id' => $id,
                        'name' => $product->getName(),
                        'sku' => $product->getSku(),
                        'qty' => $qty
                    );
                }
            }
        }
        if ($turkeys = $postObject->getTurkey()) {
            foreach ($turkeys as $id => $qty) {
                if ($qty > 0) {
                    $product = Mage::getModel('catalog/product')->load($id);
                    $items[$id] = array(
                        'id' => $id,
                        'name' => $product->getName(),
                        'sku' => $product->getSku(),
                        'qty' => $qty
                    );
                }
            }
        }

        return $items;
    }*/
}
