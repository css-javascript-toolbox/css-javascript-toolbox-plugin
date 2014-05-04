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
class CJTBlockFilesTable extends CJTxTable {
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTPackageTable
	*/
	public function __construct($dbDriver) {
		// Initialize parent.
		parent::__construct($dbDriver, 'block_files');
		// Table key.
		$this->setTableKey(array('id', 'blockId'));
	}
	
} // End class.