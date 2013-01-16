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
	* @var mixed
	*/
	protected $pages = array();
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'manage';
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function addPages() {
		// Blocks Manager page! The only Wordpress menu item we've.
		$managePageHookId= add_menu_page(
			cssJSToolbox::getText('CSS & Javascript Toolbox'), 
			cssJSToolbox::getText('CSS & Javascript Toolbox'), 
			'manage_options', 
			CJTPlugin::PLUGIN_REQUEST_ID, 
			array(&$this->controller, '_doAction')
		);
		add_action("load-{$managePageHookId}", array($this, 'getPage'));
		$this->pages[$managePageHookId] = array('handler' => 'manage');
		// Setup Page.
		$setupPageHookId = add_submenu_page(
			CJTPlugin::PLUGIN_REQUEST_ID, 
			cssJSToolbox::getText('CSS & Javascript Toolbox - Setup'), 
			cssJSToolbox::getText('Setup'), 
			'manage_options', 
			CJTPlugin::PLUGIN_REQUEST_ID . '-setup', 
			array(&$this->controller, '_doAction')
		);
		add_action("load-{$setupPageHookId}", array($this, 'getPage'));
		$this->pages[$setupPageHookId] = array('handler' => 'setup');
		// Extensions page.
		add_submenu_page(CJTPlugin::PLUGIN_REQUEST_ID, null, cssJSToolbox::getText('Extensions'), 'manage_options', null);
		// Hack Extensions menu item to point to Plugins page!
		$GLOBALS['submenu'][CJTPlugin::PLUGIN_REQUEST_ID][2][2] = admin_url('plugins.php?s=CJTE');
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Add menu pages.
		add_action('admin_menu', array(&$this, 'addPages'));
		// If not installed and not in manage page display admin notice!
		if (!CJTPlugin::getInstance()->isInstalled()) {
			add_action('admin_notices', array(&$this, 'notInstalled'));
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPage() {
		// If not installed always run the installer.
		if (!CJTPlugin::getInstance()->isInstalled()) {
			// Set controller internal parameters.
			$_REQUEST['view'] = 'installer/install';
			// create controller.
			$this->route()
			// Set Action
			->setAction('install')
			// Dispatch the call.
			->_doAction();
		}
		else { // If installed work like a pages proxy!
			// Fetch page object!
			$pageId = str_replace('load-', '', current_filter());
			$page = $this->pages[$pageId];
			// Dispatch the call.
			$this->{$page['handler']}();
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function manage() {
		// Instantiate controller! & set the action, display manage page!
		$this->route();
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
	
	/**
	* put your comment there...
	* 
	*/
	public function setup() {
		// Load setup controller to handle the request.
			// Set MVC request parameters.
			$_REQUEST['view'] = 'setup/setup';
			// Instantiate installer cotroller and fire notice action!
			$this->route()
			// Set action name.
			->setAction('setup');
	}
	
} // End class.

// Hookable!
CJTManageAccessPoint::define('CJTAjaxAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));