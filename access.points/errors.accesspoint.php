<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTErrorsAccessPoint extends CJTPageAccessPoint {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'errors';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Add menu pages.
		add_action('admin_menu', array(&$this, 'menu'), 13);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function menu() {
		// Setup Page.
		$pageHookId = add_submenu_page(
			CJTPlugin::PLUGIN_REQUEST_ID, 
			cssJSToolbox::getText('CSS & Javascript Toolbox - Errors'), 
			cssJSToolbox::getText('Error Entries'), 
			'manage_options', 
			CJTPlugin::PLUGIN_REQUEST_ID . '-errors-log', 
			array(&$this->controller, '_doAction')
		);
		// Process when its installed!!
		add_action("load-{$pageHookId}", array($this, 'getPage'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route() {
		// Set MVC request parameters.
		$this->controllerName = 'errors';
		$_REQUEST['view'] = 'errors/log';
		// Instantiate !
		parent::route()->setAction('index');
	}
	
} // End class.

// Hookable!
CJTErrorsAccessPoint::define('CJTErrorsAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));