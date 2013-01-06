<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTManageAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Set access point name!
		$this->name = 'manage';
		// Add menu pages.
		add_action('admin_menu', array(&$this, 'addMenu'));
		// Initialize Access Point base!
		parent::__construct();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function addMenu() {
		$menuTitle = cssJSToolbox::getText('CSS & Javascript Toolbox');
		// Blocks Manager page! The only Wordpress menu item we've.
		// All the other forms/grids (e.g templates-manager, etc...) is liked through this pages.
		$pageHookId= add_menu_page($menuTitle, $menuTitle, 10, CJTPlugin::PLUGIN_REQUEST_ID, array(&$this->controller, '_doAction'));
		// Carry out the request!
		$managerName = "load-{$pageHookId}";
		add_action($managerName, array($this, 'route'));
		// Add sub menu item to point to Wordpress plugins page with a search term passed!
		add_submenu_page(CJTPlugin::PLUGIN_REQUEST_ID, null, cssJSToolbox::getText('Extensions'), 10, null);
		// Hack Extensions menu item to point to Plugins page!
		$GLOBALS['submenu'][CJTPlugin::PLUGIN_REQUEST_ID][1][2] = admin_url('plugins.php?s=CJTE');
	}

	/**
	* put your comment there...
	* 
	*/
	public function route() {
		$action = 'index';
		// No actions until we installed!
		// Display installation page if not installed!
		if (!CJTPlugin::getInstance()->isInstalled()) {
			$_REQUEST['view'] = 'installer/install';	
			$action = 'install';
		}
		// Instantiate controller! & set the action
		$controller = parent::route()
														->setAction($action);
		return $controller;
	}
	
} // End class.
