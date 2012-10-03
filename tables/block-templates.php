<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
cssJSToolbox::import('framework:db:mysql:table.inc.php');

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTBlockTemplatesTable extends CJTTable {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, cssJSToolbox::$config->database->tables->blockTemplates);
	}
	
} // End class.