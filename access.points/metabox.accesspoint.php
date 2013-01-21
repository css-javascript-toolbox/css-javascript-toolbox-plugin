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
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'metabox';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Define AJAX access point!
		add_action("add_meta_boxes", array(&$this, 'route'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route() {
		$controller = false;
		// Veil access point unless CJT installed!
		if (CJTPlugin::getInstance()->isInstalled()) {
			// Set as connected object!
			$this->connected();
			// Load metabox controller!
			$this->controllerName = 'metabox';
			// Do Work!
			$controller = parent::route();			
		}
		return $controller;
	}
	
} // End class.

// Hookable!
CJTMetaboxAccessPoint::define('CJTMetaboxAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));