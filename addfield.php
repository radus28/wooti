<?php

$Vtiger_Utils_Log = true;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
include_once('../config.inc.php');
set_include_path($root_directory);
include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Block.php');

$moduleInstance = Vtiger_Module::getInstance('Products');
$blockInstance = Vtiger_Block::getInstance('LBL_CUSTOM_INFORMATION', $moduleInstance);

$fieldInstance = Vtiger_Field::getInstance("woo_id", $moduleInstance);
if ($fieldInstance === false) {
    $fieldInstance = new Vtiger_Field();
    $fieldInstance->name = 'woo_id';
    $fieldInstance->label = 'LBL_WOOCOMMERCE_ID';
    $fieldInstance->table = 'vtiger_products';
    $fieldInstance->column = 'woo_id';
    $fieldInstance->columntype = 'varchar(100)';
    $fieldInstance->uitype = 1;
    $fieldInstance->displaytype = 2;
    $fieldInstance->typeofdata = 'V~O';
    $blockInstance->addField($fieldInstance);
}

$moduleInstance = Vtiger_Module::getInstance('SalesOrder');
$blockInstance = Vtiger_Block::getInstance('LBL_CUSTOM_INFORMATION', $moduleInstance);

$fieldInstance = Vtiger_Field::getInstance("woo_id", $moduleInstance);
if ($fieldInstance === false) {
    $fieldInstance = new Vtiger_Field();
    $fieldInstance->name = 'woo_id';
    $fieldInstance->label = 'LBL_WOOCOMMERCE_ID';
    $fieldInstance->table = 'vtiger_salesorder';
    $fieldInstance->column = 'woo_id';
    $fieldInstance->columntype = 'varchar(100)';
    $fieldInstance->uitype = 1;
    $fieldInstance->typeofdata = 'V~O';
    $blockInstance->addField($fieldInstance);
}

$moduleInstance = Vtiger_Module::getInstance('Contacts');
$blockInstance = Vtiger_Block::getInstance('LBL_CUSTOM_INFORMATION', $moduleInstance);

$fieldInstance = Vtiger_Field::getInstance("woo_id", $moduleInstance);
if ($fieldInstance === false) {
    $fieldInstance = new Vtiger_Field();
    $fieldInstance->name = 'woo_id';
    $fieldInstance->label = 'LBL_WOOCOMMERCE_ID';
    $fieldInstance->table = 'vtiger_contactdetails';
    $fieldInstance->column = 'woo_id';
    $fieldInstance->columntype = 'varchar(100)';
    $fieldInstance->uitype = 1;
    $fieldInstance->typeofdata = 'V~O';
    $blockInstance->addField($fieldInstance);
}
