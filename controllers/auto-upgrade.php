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
		// Always REQUEST CJT server even if not key activated yet.
		// FREE edition will just do normal check @Wordpress:reporioty
		if (!isset($activeLicenses[CJTSetupModel::EDD_PRODUCT_NAME]) && (CJTPlugin::Edition != 'free')) {
			$activeLicenses[CJTSetupModel::EDD_PRODUCT_NAME] = array(
				'plugin' => array('Version' => CJTPlugin::VERSION, 'AuthorName' => 'CTK'),
				'license' => array('key' => str_repeat('0', 32)),
				'component' => array('pluginBase' => 'css-javascript-toolbox/css-js-toolbox.php')
			);
		}
		// Import EDD updater Class!
		cssJSToolbox::import('framework:third-party:easy-digital-download:auto-upgrade.class.php');
		// Activate Automatic upgrade for all activated licenses/components!
		foreach ($activeLicenses as $name => $state) {
			// Initializingn vars for a single state/component!
			$pluginFile = ABSPATH . PLUGINDIR . '/' . $state['component']['pluginBase'];
			// Stop using Cached Data as it causes issue, always 
			// get fresh plugin data.
			$plugin = get_plugin_data($pluginFile);
			$license =& $state['license'];
			// Edd API parameter to be send along with he check!
			$requestParams= array(
				'version' => $plugin['Version'],
				'author' => $plugin['AuthorName'],
				'license' => $license['key'],
				'item_name' => $name,
			);
			// Set EDD Automatic Updater!
			$updated = new CJT_EDD_SL_Plugin_Updater($cjtWebServer, $pluginFile, $requestParams);
		}
	}
} // End class.
