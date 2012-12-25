<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTV02Upgrade extends CJTHookableClass {
	
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
		return new CJTV02Upgrade();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		echo "upgrade version 0.2!<br>";
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgradeBlocks() {
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgradeTemplates() {
		
	}
	
} // End class.