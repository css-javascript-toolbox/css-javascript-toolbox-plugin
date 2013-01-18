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
	const EDD_PRODUCT_NAME = 'CSS Javascript Toolbox';
	
	/**
	* 
	*/
	const LICENSES_CACHE = 'cache.CJTSetupModel.licenses';

	/**
	* put your comment there...
	* 
	* @param mixed $component
	* @param mixed $action
	* @param mixed $state
	*/
	public function cacheState($component, $action,  $state) {
		// Read cache from db!
		$cache = get_option(self::LICENSES_CACHE);
		// Add action to the state object!
		$state['action'] = $action;
		// Cache object!
		$cache[$component['name']] = $state;
		update_option(self::LICENSES_CACHE, $cache);
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
		//$request['licenseName'] = $license['name'];
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
	* @param mixed $compoment
	* @param mixed $field
	*/
	public function getStateStruct($compoment, $struct = null) {
		// Read all licenses from db!
		$licensesCache = get_option(self::LICENSES_CACHE);
		// If not set return clean empty array!
		$componentName = $compoment['name'];
		$state = isset($licensesCache[$componentName]) ? $licensesCache[$componentName] : false;
		return ($struct ? ($state[$struct] ? $state[$struct] : false)  : $state);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getRequestComponent() {
		return $_REQUEST['component'] ? $_REQUEST['component']: array('name' => self::EDD_PRODUCT_NAME);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $state
	*/
	public function removeStateCache($state) {
		// Initializing!
		$componentName = $state['component']['name'];
		// Read all cached licenses!
		$cachedStates = get_option(self::LICENSES_CACHE);
		// Set return value!
		$result = isset($cachedStates[$componentName]) ? 'valid' : 'invalid';
		// Remove component license even if its not exists, nothing will happen!  
		unset($cachedStates[$componentName]);
		// Update cache list!
		update_option(self::LICENSES_CACHE, $cachedStates);
		return $result;
	}
	
} // End class.