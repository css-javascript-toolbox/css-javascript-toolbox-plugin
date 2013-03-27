<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Enable automatic upgrades CJT Plugin
* and all its installed extensions!
* 
* Special type of access point that will do the job and 
* then it'll unload itself!
* 
* @author Ahmed Hamed
*/
class CJTAutoUpgradeAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'auto-upgrade';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Only if permitted!
		if ($this->hasAccess()) {
			add_action('admin_init', array(&$this, 'route'), 10, 0);
		}
	}
	
	/**
	* This access point is to do internal jobs without
	* taking the place of the activate controller that requested
	* by client!
	* 
	* @return Boolean false
	*/
	public function connected() {
		throw new Exception('I\'ll never be the connected object!');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route($loadView = false, $request = null) {
		// Load Auto Upgrade controller!
		$this->controllerName = 'auto-upgrade';
		parent::route($loadView, $request)
		// Set action name to autoUpgrade
		->setAction('enable')
		// fire action to enable automatic upgrade!
		->_doAction();
	}
	
} // End class.

// Hookable!
CJTAutoUpgradeAccessPoint::define('CJTAutoUpgradeAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));