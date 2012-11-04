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
class CJTTemplateRevisionTable extends CJTxTable {
	
	/** */
	const FLAG_LAST_REVISION = 0x01;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, 'template_revisions');
	}
	
} // End class.