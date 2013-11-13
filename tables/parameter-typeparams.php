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
class CJTParameterTypeparamsTable extends CJTxTable {
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTPackageTable
	*/
	public function __construct($dbDriver) {
		// Initialize parent.
		parent::__construct($dbDriver, 'parameter_typeparams');
	}
	
} // End class.