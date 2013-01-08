<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTAjaxAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Set access point name!
		$this->name = 'ajax';
		// Define CJT AJAX access point!
		add_action("wp_ajax_{$this->pageId}_api", array(&$this, 'route'));
		// Initialize Access Point base!
		parent::__construct();
	}

	/**
	* put your comment there...
	* 
	*/
	public function route() {
		$controller = false;
		// Veil access point unless CJT installed or the controller is installer (to allow instalaltion throught AJAX)!
		if (CJTPlugin::getInstance()->isInstalled() || ($this->controllerName == 'installer')) {
			// Instantiate controller.
			$controller = parent::route();
			// Dispatch the call as its originally requested from ajax action!
			$action = "wp_ajax_{$this->pageId}_{$_REQUEST['CJTAjaxAction']}";
			// Fire Ajax action.
			do_action($action);			
		}
		return $controller;
	}
	
} // End class.
