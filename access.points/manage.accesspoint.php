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
		// If not installed and not in manage page display admin notice!
		if (!CJTPlugin::getInstance()->isInstalled()) {
			add_action('admin_notices', array(&$this, 'notInstalled'));
		}
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
		add_action($managerName, array($this, 'manage'));
		// Add sub menu item to point to Wordpress plugins page with a search term passed!
		add_submenu_page(CJTPlugin::PLUGIN_REQUEST_ID, null, cssJSToolbox::getText('Extensions'), 10, null);
		// Hack Extensions menu item to point to Plugins page!
		$GLOBALS['submenu'][CJTPlugin::PLUGIN_REQUEST_ID][1][2] = admin_url('plugins.php?s=CJTE');
	}

	/**
	* put your comment there...
	* 
	*/
	public function manage() {
		$action = 'index';
		// No actions until we installed!
		// Display installation page if not installed!
		if (!CJTPlugin::getInstance()->isInstalled()) {
			$_REQUEST['view'] = 'installer/install';	
			$action = 'install';
		}
		// Instantiate controller! & set the action, display manage page!
		$controller = $this->route()
														->setAction($action);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function notInstalled() {
		// If we're not in manage page (not controller loaded in $this access point)
		// notife user for installation.
		if (!$this->isLoaded())	{
			// Set MVC request parameters.
			$_REQUEST['view'] = 'installer/notice';
			// Instantiate installer cotroller and fire notice action!
			$this->route()
			// Set action name.
			->setAction('notInstalledNotice')
			// Fire action!
			->_doAction();
		}
	}
	
} // End class.
