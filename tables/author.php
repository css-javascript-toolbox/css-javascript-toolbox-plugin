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
class CJTAuthorTable extends CJTxTable {
	
	/** 
	* 
	* 
	*/
	const FLAG_SYS_AUTHOR = 1;
	
	/**
	* 
	* 
	*/
	const FLAG_LOCAL_AUTHOR = 2;
	
	/**
	 * 
	 * 
	*/
	const FLAG_GLOBAL_AUTHOR = 4;
	
	/**
	* 
	*/
	const WORDPRESS_AUTHOR_ID = 1;
		
	/**
	* put your comment there...
	* 
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, 'authors');
	}
	
} // End class.