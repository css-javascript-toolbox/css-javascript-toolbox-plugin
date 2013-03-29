<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTAutoUpgradeController extends CJTController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'setup');
	
	/**
	* put your comment there...
	* 
	*/
	protected function enableAction() {
		// Initializing!
		$model = $this->model;
		$cjtWebServer = cssJSToolbox::getCJTWebSiteURL();
		// Get all CJT-Plugins (Include CJT Plugin itself + all its extensions) that has activate
		// license key!
		$activeLicenses = $model->getStatedLicenses();
		// Import EDD updater Class!
		if (!class_exists('EDD_SL_Plugin_Updater')) {
			cssJSToolbox::import('framework:third-party:easy-digital-download:auto-upgrade.class.php');
		}
		// Activate Automatic upgrade for all activated licenses/components!
		foreach ($activeLicenses as $name => $state) {
			// Initializingn vars for a single state/component!
			$plugin =& $state['plugin'];
			$license =& $state['license'];
			$componentPluginPath = ABSPATH . PLUGINDIR . "/{$state['component']['pluginBase']}";
			// Edd API parameter to be send along with he check!
			$requestParams= array(
				'version' => $plugin['Version'],
				'author' => $plugin['AuthorName'],
				'license' => $license['key'],
				'item_name' => $name,
			);
			// Set EDD Automatic Updater!
			$updated = new EDD_SL_Plugin_Updater($cjtWebServer, $componentPluginPath, $requestParams);
		}
	}
} // End class.
