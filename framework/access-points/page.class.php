<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTPageAccessPoint extends CJTAccessPoint {

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
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
			$this->processRequest();
		}
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
	protected abstract function processRequest();
	
} // End class.

// Hookable!
CJTPageAccessPoint::define('CJTPageAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));