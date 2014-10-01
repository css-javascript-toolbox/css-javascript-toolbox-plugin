<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTSetupAccessPoint extends CJTPageAccessPoint {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'setup';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Only if permitted!
		if ($this->hasAccess()) {
			// Add menu page.
			add_action('admin_menu', array(&$this, 'menu'), 12);
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
			cssJSToolbox::getText('CSS & Javascript Toolbox - Setup'), 
			cssJSToolbox::getText('Setup'), 
			'administrator', 
			CJTPlugin::PLUGIN_REQUEST_ID . '-setup', 
			array(&$this->controller, '_doAction')
		);
		// Process when its installed!!
		add_action("load-{$pageHookId}", array($this, 'getPage'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route($loadView = null, $request = array('view' => 'setup/setup')) {
		// Set MVC request parameters.
		parent::route($loadView, $request)
		// Set action name.
		->setAction('setup');
	}
	
} // End class.

// Hookable!
CJTSetupAccessPoint::define('CJTSetupAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));