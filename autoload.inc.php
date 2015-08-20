<?php
/**
* 
*/

// NOTE: NOT ALL CLASSED IS AUTOLOADED YET! ONLY FEW CLASSES
// IS AUTOLOADED. ONLY CLASSES WITH NAME MAPPED TO TE PHYSICAL PATH
// IS AUTOLOADED!!
require_once CJTOOLBOX_FRAMEWORK . '/autoload/loader.php';
$CJTAutoLoad = CJT_Framework_Autoload_Loader::autoLoad('CJT', CJTOOLBOX_PATH);

// Old class maps.
// Only commonly-used classed will be mapped here.
$map = $CJTAutoLoad->map();

// xTable class
$map->offsetSet('CJTxTable', 'framework/db/mysql/xtable.inc.php');
$map->offsetSet('CJTTable', 'framework/db/mysql/table.inc.php');
$map->offsetSet('CJTBlockPinsTable', 'tables/block-pins.php');
$map->offsetSet('CJTBlocksTable', 'tables/blocks.php');
$map->offsetSet('CJTBlockFilesTable', 'tables/block-files.php');
$map->offsetSet('CJTBlockModel', 'models/block.php');
$map->offsetSet('CJTBlocksModel', 'models/blocks.php');
$map->offsetSet('CJTAccessPoint', 'framework/access-points/access-point.class.php');
$map->offsetSet('CJTPageAccessPoint', 'framework/access-points/page.class.php');


# Composer Autoloads (All the above autoloads is deprecated and will be revmoed later)
require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';