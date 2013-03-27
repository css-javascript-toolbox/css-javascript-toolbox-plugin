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
		// Only if permitted!
		if ($this->hasAccess()) {
			// Add CJT Block metabox!
			add_action("add_meta_boxes", array(&$this, 'route'), 10, 0);
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route($loadView = null, $request = null) {
		$controller = false;
		// Veil access point unless CJT installed!
		if (CJTPlugin::getInstance()->isInstalled()) {
			// Only if permitted!
			if ($this->hasAccess()) {
				// Set as connected object!
				$this->connected();
				// Load metabox controller!
				$this->controllerName = 'metabox';
				// Do Work!
				$controller = parent::route($loadView, $request);
			}
		}
		return $controller;
	}
	
} // End class.

// Hookable!
CJTMetaboxAccessPoint::define('CJTMetaboxAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));