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
		$extensions =& CJTPlugin::getInstance()->extensions();
		// Get all CJT-Plugins (Include CJT Plugin itself + all its extensions) that has activate
		// license key!
		$activeLicenses = $model->getStatedLicenses();
		// Import EDD updater Class!
		cssJSToolbox::import('framework:third-party:easy-digital-download:auto-upgrade.class.php');
		// Activate Automatic upgrade for all activated licenses/components!
		foreach ($activeLicenses as $name => $state) {
			// Get extension def doc.
			// Act only if extension has XMl DOC! This might happened i fthe extension
			// removed while its key still in the database
			if ($extDef = $extensions->getDefDoc(dirname($state['component']['pluginBase']))) {
				// Check CJT Server only if updateSrc points to Wordpress Repository
				$updateSrcServer = (string) $extDef->license->attributes()->updateSrc;
				if (!$updateSrcServer || ($updateSrcServer == 'CJT')) {
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
		}
	}
} // End class.
