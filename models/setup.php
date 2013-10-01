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
	const EDD_PRODUCT_NAME = 'CJT Pro';
	
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
	public function dispatchEddCall($action, $component, $license) {
		// Activating License key thorugh EDD APIs!		
		$request['edd_action'] = "{$action}_license";
		$request['item_name'] = urlencode($component['name']);
		$request['license'] = $license['key'];
		/* CJT Extra Fields For EDD */
		$request['CJTEFFEDD_licenseName'] = $license['name'];
		// Request the server!
		$response = wp_remote_get(add_query_arg($request, cssJSToolbox::getCJTWebSiteURL()));
		// We need only the JSON object returned by EDD APIs.
		$response = @json_decode(wp_remote_retrieve_body($response), true);
		// If request error compaitble the response object to be used!
		if (!$response) {
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
		$component['name'] = self::EDD_PRODUCT_NAME;
		$component['pluginBase'] = CJTOOLBOX_PLUGIN_BASE;
		return $component;
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
	* @param mixed $compoment
	* @param mixed $field
	*/
	public function getStateStruct($compoment, $struct = null) {
		// Read all licenses from db!
		$licensesCache =& $this->getLicenses();
		// If not set return clean empty array!
		$componentName = $compoment['name'];
		$state = isset($licensesCache[$componentName]) ? $licensesCache[$componentName] : false;
		return ($struct ? ($state[$struct] ? $state[$struct] : false)  : $state);
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