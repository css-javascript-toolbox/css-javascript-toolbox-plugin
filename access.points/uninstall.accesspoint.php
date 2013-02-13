<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTUninstallAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'uninstall';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Register uninstall hook!
		if (CJTPlugin::getInstance()->isInstalled() && $this->hasAccess()) {
			register_uninstall_hook(CJTOOLBOX_PLUGIN_FILE, array(&$this, 'route'));	
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route() {
		// Load default controller!
		$this->controllerName = 'default';
		$controller = parent::route()
		// Fire uninstall action!
		->setAction('uninstall')
		->_doAction();
		return $controller;
	}
	
} // End class.

// Hookable!
CJTUninstallAccessPoint::define('CJTUninstallAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));