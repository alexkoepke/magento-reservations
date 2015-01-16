<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

//$model = Mage::getModel('eav/entity_setup','core_setup');

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute('catalog_product', 'reservation', array(
    'backend'       => '',
    'source'        => '',
    'group'		=> 'General',
    'label'         => 'Reservation',
    'input'         => 'select',
    'class'         => '',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'       => true,
    'required'      => false,
    'user_defined'  => true,
    'default'       => '0',
    'visible_on_front' => false,
    'option'     => array (
        'values' => array(
            1 => 'Ham',
            2 => 'Turkey',
        )
    ),

));
$attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'reservation');
$attributeModel->setUsedInProductListing(1);
$attributeModel->save();

