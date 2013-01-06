<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTMetaboxAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Set access point name!
		$this->name = 'metabox';
		// Define AJAX access point!
		add_action("add_meta_boxes", array(&$this, 'route'));
		// Initialize Access Point base!
		parent::__construct();
	}

	/**
	* put your comment there...
	* 
	*/
	public function route() {
		$controller = false;
		// Veil access point unless CJT installed!
		if (CJTPlugin::getInstance()->isInstalled()) {
			$this->controllerName = 'metabox'; // Impersonating!
			// Instantiate controller.
			$controller = parent::route();			
		}
		return $controller;
	}
	
} // End class.
