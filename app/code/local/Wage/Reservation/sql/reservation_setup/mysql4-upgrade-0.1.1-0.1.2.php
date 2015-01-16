<?php

$installer = $this;

/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    ALTER TABLE  {$this->getTable('reservation/reservation_order_item')} ADD  `product_price` DECIMAL( 20, 2 ) NOT NULL ;
");

$installer->endSetup();
