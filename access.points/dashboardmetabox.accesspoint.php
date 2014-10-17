<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTDashboardMetaboxAccessPoint extends CJTAccessPoint {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'dashboard-metabox';
	}

	/**
	* put your comment there...
	* 
	*/
	public function createMetabox() {
		if (CJTPlugin::getInstance()->isInstalled()) {
			wp_add_dashboard_widget('cjt-statistics', cssJSToolbox::getText('CSS & Javascript Toolbox'), array($this, 'route'));	
		}
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		add_action('wp_dashboard_setup', array(&$this, 'createMetabox'), 10, 0);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route($loadView = false, $request = null) {
		// Load Auto Upgrade controller!
		$this->controllerName = 'default';
		parent::route($loadView, $request)
		// Set action name to autoUpgrade
		->setAction('dashboardMetabox')
		// fire action to enable automatic upgrade!
		->_doAction();
	}
	
} // End class.