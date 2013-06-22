<?php
/**
* 
*/

// NOTE: NOT ALL CLASSED IS AUTOLOADED YET! ONLY FEW CLASSES
// IS AUTOLOADED AND CAN BE DETERMINDED BY THE PATH MAP OF THE NAME
// LIKE AUTOLOAD CLASS IN THE LINE BELOW!
require_once CJTOOLBOX_FRAMEWORK . '/autoload/loader.php';
$CJTAutoLoad = CJT_Framework_Autoload_Loader::autoLoad('CJT', CJTOOLBOX_PATH);

// Old class maps.
// Only commonly-used classed will be mapped here.

// xTable class
$CJTAutoLoad->map()->offsetSet('CJTxTable', 'framework/db/mysql/xtable.inc.php');
