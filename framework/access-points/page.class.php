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
	public function getPage() {
		// If not installed always run the installer.
		if (!CJTPlugin::getInstance()->isInstalled()) {
			$installedAccessPoint =CJTPlugin::getInstance()->getAccessPoint('installer');
			// Redirect menu call back to the installer access point!
			$this->controller = $installedAccessPoint->installationPage();
			// Stop not installed admin notice!
			$installedAccessPoint->stopNotices();
		}
		else { // If installed work like a pages proxy!
			// Set as the connected object!
			$this->connected();
			// Process the request!
			$this->route();
		}
	}
	
} // End class.

// Hookable!
CJTPageAccessPoint::define('CJTPageAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));