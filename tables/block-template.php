<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTBlockTemplateTable extends CJTxTable {
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTTemplatesTable
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, 'block_templates');
	}
	
} // End class.