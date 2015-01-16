<?php

$installer = $this;

/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reservation/reservation_order')};

	CREATE TABLE {$this->getTable('reservation/reservation_order')} (
		`reservation_id` int(11) NOT NULL auto_increment,
		`store_id` int(11) NOT NULL,
		`email` VARCHAR(255) NOT NULL,
		`name` VARCHAR(255) NOT NULL,
		`reserve_date` datetime NOT NULL,
		`sign_up` int(4) NOT NULL,
		`created_date` datetime NOT NULL,
		PRIMARY KEY  (`reservation_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	DROP TABLE IF EXISTS {$this->getTable('reservation/reservation_order_item')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('reservation/reservation_order_item')} (
	    `id` int(11) unsigned NOT NULL auto_increment,
	    `reservation_id` int(11) default NULL,
	    `product_id` int(11) NOT NULL,
		`sku` VARCHAR( 64 ) NOT NULL,
		`product_name` varchar(255) NOT NULL,
		`ordered_qty` int(4) NOT NULL,
	    PRIMARY KEY  (`id`),
	    KEY `FK_RESERVATION_ID` (`reservation_id`),
	    CONSTRAINT `FK_RESERVATION_ID` FOREIGN KEY (`reservation_id`) REFERENCES `{$this->getTable('reservation/reservation_order')}` (`reservation_id`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
