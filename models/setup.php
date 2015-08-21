<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTSetupModel {
	
	/**
	* 
	*/
	const LICENSES_CACHE = 'cache.CJTSetupModel.licenses';

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $licenses;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Read all cached licenses!
		$this->licenses = get_option(self::LICENSES_CACHE);
		// Make sure its array!
		if (!is_array($this->licenses)) {
			$this->licenses = array();
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $component
	* @param mixed $action
	* @param mixed $state
	* @param mixed $pluginBase
	*/
	public function cacheState($component, $action,  $state) {
		// Read cache from db!
		$cache =& $this->getLicenses();
		// Add action to the state object!
		$state['action'] = $action;
		// Cache Plugin data!
		$state['plugin'] = get_plugin_data(ABSPATH . PLUGINDIR . "/{$component['pluginBase']}", false);
		// Cache object!
		$cache[$component['name']] = $state;
		update_option(self::LICENSES_CACHE, $cache);
		// update local Cache!
		$this->licenses = $cache;
		// return action name
		return $action;		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $action
	* @param mixed $component
	* @param mixed $license
	*/
	public function dispatchLicenseAction($action, $component, $license) {
		# Get Plugin File from component base name
		$pluginFile = WP_PLUGIN_DIR . $component[ 'pluginBase' ];
		# Get CJT Store object
		$store = new CJTStore( $component [ 'name' ], $license[ 'key' ], $pluginFile );
		# Build method name from the given action
		$methodName = "{$action}License";
		try {
			# Call requested method
			$result = $store->$methodName( $license[ 'name' ] );
			# Build response object locally
			# The structure is taken from EDD license extension as it was used here
			# when the plugin is orignally developed
			if ( $result ) { // Success operation
				$response['license'] = 'valid';
			}
			else { // Operation faild
				$response['license'] = 'invalid';
			}
		}
		catch ( CJTServicesAPICallException $exception ) {
			// If request error compaitble the response object to be used!	
			$response = array('license' => 'error', 'component' => $component['name']);
		}
		return $response;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getCJTComponentData() {
		$component = array();
		// Set info!
		$component['pluginBase'] = CJTOOLBOX_PLUGIN_BASE;
		$component['title'] = 'CSS & Javascript Toolbox';
		return $component;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $component
	*/
	public function getExtensionProductTypes($component) {
		# Initialize 
		$types = array();
		# Extension plugin file
		$pluginDirName = dirname($component['pluginBase']);
		$pluginXMLFile = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 
									$pluginDirName . DIRECTORY_SEPARATOR . 
									$pluginDirName . '.xml';
		# Use XML
		$exDef = new SimpleXMLElement(file_get_contents($pluginXMLFile));
		# Register XPath namespace
		$license = $exDef->license;
		$license->registerXPathNamespace('ext', 'http://css-javascript-toolbox.com/extensions/xmldeffile');
		# Get types
		$typesSrc = $exDef->license->xpath('name');
		foreach ($typesSrc as $type) {
			# Product name
			$name = (string) $type;
			# Old Definiton document doesnt support text attributes.
			# If not there use name node value as TEXT
			if (!$text = ((string) $type->attributes()->text)) {
				$text = $name;
			}
			# Add to list
			$types[$name] = array('name' => $name, 'text' => $text);
		}
		return $types;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getLicenses() {
		return $this->licenses;
	}
	
	/**
	* Get list of all cached licenses that has 
	* a license key in $state state!
	* 
	* @param mixed $state
	*/
	public function getStatedLicenses($state = 'activate') {
		// Initializing!
		$statedList = array();
		// Read all cached licenses!
		$cacheList =& $this->getLicenses();
		// Find license with the requested state!
		foreach ($cacheList as $key => $license) {
			if ($license['action'] == $state) {
				$statedList[$key] = $license;
			}
		}
		return $statedList;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $licenseTypes
	* @param mixed $compoment
	* @param mixed $struct
	*/
	public function getStateStruct($licenseTypes, $struct = null) {
		// INit 
		$state = null;
		// Read all licenses from db!
		$licensesCache =& $this->getLicenses();
		// Find license 
		foreach ($licenseTypes as $type) {
			# Get product name to search
			$productName = $type['name'];
			if (isset($licensesCache[$productName])) {
				# Get product state
				$state = $licensesCache[$productName];
				# Filter to section
				if ($struct) {
					$state = $state[$struct];
				}
				# ALways push product name
				$state['productName'] = $productName;
				# Exit for
				break;
			}
		}
		return $state;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $state
	*/
	public function removeCachedLicense($state) {
		// Initializing!
		$componentName = $state['component']['name'];
		// Read all cached licenses!
		$cachedStates =& $this->getLicenses();
		// Set return value!
		$result = isset($cachedStates[$componentName]) ? 'valid' : 'invalid';
		// Remove component license even if its not exists, nothing will happen!  
		unset($cachedStates[$componentName]);
		// Update cache list!
		update_option(self::LICENSES_CACHE, $cachedStates);
		// Update local cache!
		$this->licenses = $cachedStates;
		return $result;
	}
	
} // End class.