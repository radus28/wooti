<?php
error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_DEPRECATED));
include_once('../config.inc.php');
set_include_path($root_directory);
include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Block.php');

require 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
$emm = new VTEntityMethodManager($adb);
$emm->addEntityMethod("Products", "productsToWoo", "woo28/out/products.php", "productsToWoo");

echo "Done";
