<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies!
cssJSToolbox::import('installer:upgrade:upgrade.class.php');

/**
* 
*/
class CJTV02Upgrade extends CJTUpgradeNonTabledVersions {
	
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
	public function blocks() {
		// Transform blocks one by one as there're data need to be mainpulated!
		foreach ($this->blocks as $key => &$block) {
			// Give a name to the block!
			$block['name'] = "Block #{$key}";
			$block['state'] = 'active'; // Defautt to active!
			$block['location'] = 'header'; // Output in header!
			// Fix links as it saved with /n/r as line end and it got splitted using only /n!
			// This is  a Bug in version 0.2! Only the last link is correct but the others carry /r at the end!
			$block['links']	= str_replace("\r\n", "\n", $block['links']);
			// Upgrade Block!
			$this->blocks->upgrade();
		}
		// Save all changes!
		$this->blocks->model->save();
		// Do other cleanup and common behavior!
		return parent::blocks();
	}
	
} // End class.