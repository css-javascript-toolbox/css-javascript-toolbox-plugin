<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTExtensionsAccessPoint extends CJTPageAccessPoint {
	
	/**
	* 
	*/
	const PLUGINS_PAGE_SEARCH_TERM = 'CJT-Extension';
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'extensions';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Add menu pages.
		add_action('admin_menu', array(&$this, 'menu'), 11);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function menu() {
		// Extensions page.
		add_submenu_page(CJTPlugin::PLUGIN_REQUEST_ID, null, cssJSToolbox::getText('Extensions'), 'manage_options', null);
		// Hack Extensions menu item to point to Plugins page!
		$GLOBALS['submenu'][CJTPlugin::PLUGIN_REQUEST_ID][2][2] = admin_url('plugins.php?s=' . self::PLUGINS_PAGE_SEARCH_TERM);
		// When plugins page loaded!
		add_action('load-plugins.php', array($this, 'getPage'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function processRequest() {
		// Load extensions view throughjt the default controller!
		$_REQUEST['view'] ='extensions/plugins-list';
		// Create controller!
		$this->route()
		// Set Action name!
		->setAction('extensions')
		// Dispatch the call!
		->_doAction();
	}
	
} // End class.

// Hookable!
CJTExtensionsAccessPoint::define('CJTExtensionsAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));