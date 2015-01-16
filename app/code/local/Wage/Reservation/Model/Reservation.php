<?php

class Wage_Reservation_Model_Reservation extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reservation/reservation');
    }

    public function getHamProducts()
    {
        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute('reservation');

        $hamProducts = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('reservation', array('eq' => $attribute->getSource()->getOptionId('Ham')))
            ->addAttributeToFilter('status', array('eq' => 1))
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();

        $ham = array();
        foreach ($hamProducts as $hamProduct) {
            $ham[$hamProduct->getEntityId()] = array(
                'name'=>$hamProduct->getName(),
                'price'=>$hamProduct->getFinalPrice(),
            );

        }

        return $ham;
    }

    public function getTurkeyProducts()
    {
        $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute('reservation');

        $turkeyProducts = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('reservation', array('eq' => $attribute->getSource()->getOptionId('Turkey')))
            ->addAttributeToFilter('status', array('eq' => 1))
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();

        $turkey = array();
        foreach ($turkeyProducts as $turkeyProduct) {
            $turkey[$turkeyProduct->getEntityId()] = array(
                'name'=>$turkeyProduct->getName(),
                'price'=>$turkeyProduct->getFinalPrice(),
            );
        }

        return $turkey;
    }

    public function saveReservation($data, $storeId)
    {
        $reservation = $this->setStoreId($storeId)
            ->setName($data->getName())
            ->setEmail($data->getEmail())
            ->setReserveDate($data->getReserveDate())
            ->setSignUp($data->getSignUp())
            ->setCreatedDate(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())))
            ->save()
        ;

        $reservationId = $reservation->getReservationId();
        $items = $this->getItems($data);
        foreach ($items as $item) {
            $reservationItem = Mage::getModel('reservation/reservationitem')
                ->setReservationId($reservationId)
                ->setProductId($item['id'])
                ->setProductName($item['name'])
                ->setSku($item['sku'])
                ->setOrderedQty($item['qty'])
                ->setProductPrice($item['price'])
                ->save()
            ;
        }
        return $reservationId;
    }

    public function getItems($postObject)
    {
        $items = array();
        if ($hams = $postObject->getHam()) {
            foreach ($hams as $id => $qty) {
                if ($qty > 0) {
                    $product = Mage::getModel('catalog/product')->load($id);
                    $price = $this->getProductFinalPrice($id);
                    $items[$id] = array(
                        'id' => $id,
                        'name' => $product->getName(),
                        'sku' => $product->getSku(),
                        'qty' => $qty,
                        'price' => $price
                    );
                }
            }
        }
        if ($turkeys = $postObject->getTurkey()) {
            foreach ($turkeys as $id => $qty) {
                if ($qty > 0) {
                    $product = Mage::getModel('catalog/product')->load($id);
                    $price = $this->getProductFinalPrice($id);
                    $items[$id] = array(
                        'id' => $id,
                        'name' => $product->getName(),
                        'sku' => $product->getSku(),
                        'qty' => $qty,
                        'price' => $price
                    );
                }
            }
        }

        return $items;
    }

    public function getProductFinalPrice($id)
    {
        $products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', $id)
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();

        return $products->getFirstItem()->getFinalPrice();
    }
}
