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
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'ajax';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Define CJT AJAX access point!
		add_action("wp_ajax_{$this->pageId}_api", array(&$this, 'route'), 10, 0);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route($loadView = null, $request = null) {
		// Initializing!
		$controller = false;
		// Controllers allowed to be Loaded if not installed
		$notInstalledAllowedControllers = array('installer', 'setup');
		// Veil access point unless CJT installed or the controller is installer (to allow instalaltion throught AJAX)!
		if (CJTPlugin::getInstance()->isInstalled() || in_array($this->controllerName, $notInstalledAllowedControllers)) {
			// Connected!
			$this->connected();
			// IF Module-Prefix passed THEN Point to correct Controller path 
			if (isset($_REQUEST['cjtajaxmodule'])) {
				# try to get module associated to passed module
				$accessPointClassLoader = CJT_Framework_Autoload_Loader::autoLoad($_REQUEST['cjtajaxmodule']);
				if ($accessPointClassLoader) {
					$this->overrideControllersPath = $accessPointClassLoader->getPath() . DIRECTORY_SEPARATOR . 'controllers';	
					$this->overrideControllersPrefix = $accessPointClassLoader->getPrefix();
				}
			}
			// Instantiate controller.
			$controller = parent::route($loadView, $request);
			// Dispatch the call as its originally requested from ajax action!
			$action = "wp_ajax_{$this->pageId}_{$_REQUEST['CJTAjaxAction']}";
			// Fire Ajax action.
			do_action($action);			
		}
		return $controller;
	}
	
} // End class.

// Hookable!
CJTAjaxAccessPoint::define('CJTAjaxAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));
