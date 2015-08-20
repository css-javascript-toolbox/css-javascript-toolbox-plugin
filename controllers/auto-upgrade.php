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
					$license =& $state['license'];
					// Set EDD Automatic Updater!
					CJTStoreUpdate::autoUpgrade( $name, $license, $pluginFile );
					####$updated = new CJT_EDD_SL_Plugin_Updater($cjtWebServer, $pluginFile, $requestParams);
				}
			}
		}
		CJTStoreUpdate::autoUpgrade( 'CSS & JavaScript Toolbox Plus', '445270200e20f2e7812e5378b07e405a', CJTPLUS_PLUGIN_FILE );
	}
} // End class.
