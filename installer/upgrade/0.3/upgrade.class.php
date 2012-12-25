<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTV03Upgrade extends CJTHookableClass {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize hookable!
		parent::__construct();
	}

	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJTV03Upgrade();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		echo "upgrade version 0.3!<br>";
		return $this;
	}
	
} // End class.