<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTPackagesAccessPoint extends CJTPageAccessPoint {

	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'packages-manager';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Only if permitted!
		if ($this->hasAccess()) {
			// Add menu page.
			add_action('admin_menu', array(&$this, 'menu'), 11);
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function menu() {
		// Setup Page.
		$pageHookId = add_submenu_page(
			CJTPlugin::PLUGIN_REQUEST_ID, 
			cssJSToolbox::getText('CJT Manage - Packages'),
			cssJSToolbox::getText('Packages'),
			'administrator', 
			CJTPlugin::PLUGIN_REQUEST_ID . '-packages', 
			array(&$this->controller, '_doAction')
		);
		// Process when its installed!!
		add_action("load-{$pageHookId}", array($this, 'getPage'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route($loadView = null, $request = array('view' => 'packages/manager')) {
		// Load package manager view  through the default controller.
		$this->controllerName = 'default';
		// Set MVC request parameters.
		parent::route($loadView, $request)
		// Fire 'display' action.
		->setAction('display');
	}
	
} // End class.

// Hookable!
CJTPackagesAccessPoint::define('CJTPackagesAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));