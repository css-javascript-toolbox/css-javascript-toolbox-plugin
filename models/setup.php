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
	const EDD_PRODUCT_NAME = 'CSS & Javascript Toolbox';
	
	/**
	* 
	*/
	const LICENSES_CACHE = 'cache.CJTSetupModel.licenses';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $inputs;
	
	/**
	* put your comment there...
	* 
	* @param mixed $component
	* @param mixed $license
	*/
	public function activate($component, $license) {
		// Call EDD APIs to actiavte the license.
		$state = $this->dispatchEddCall('activate', $component, $license);
		// Cache the returned response even if its valid or invalid!
		$this->cacheComponentLicense($component['name'], $license, $state);
		// Return State as it saved in the database!
		return $this->getComponentLicenseType($component, 'state');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $license
	* @param mixed $state
	*/
	public function cacheComponentLicense($name, $license, $state) {
		// Read cache from db!
		$cache = get_option(self::LICENSES_CACHE);
		// Build cache object.
		$component['license'] = $license;
		$component['internals'] = current_time('mysql');
		$component['state'] = $state ? $state : array('license' => 'unknown');
		// Cache object!
		$cache[$name] = $component;
		update_option(self::LICENSES_CACHE, $cache);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $component
	* @param mixed $license
	*/
	public function check($component, $license) {
		$state = $this->dispatchEddCall('check', $component, $license);
		// Cache the returned response even if its valid or invalid!
		$this->cacheComponentLicense($component['name'], $license, $state);
		// Return State as it saved in the database!
		return $this->getComponentLicenseType($component, 'state');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $action
	* @param mixed $component
	* @param mixed $license
	*/
	public function dispatchEddCall($action, $component, $license) {
		// Initializing.
		$http = new WP_Http();
		$url = cssJSToolbox::getCJTWebSiteURL();
		// Activating License key thorugh EDD APIs!		
		$request['edd_action'] = "{$action}_license";
		$request['item_name'] = $component['name'];
		$request['license'] = $license['key'];
		$request['licenseName'] = $license['name'];
		// Build request string query string.
		$request = http_build_query($request);
		// Request the server!
		$response = $http->get("{$url}?{$request}");
		// We need only the JSON object returned by EDD APIs.
		$response = @json_decode($response['body'], true);
		return $response;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $compoment
	* @param mixed $field
	*/
	public function getComponentLicenseType($compoment, $type = null) {
		// Read all licenses from db!
		$licensesCache = get_option(self::LICENSES_CACHE);
		// If not set return clean empty array!
		$componentName = $compoment['name'];
		$license = isset($licensesCache[$componentName]) ? $licensesCache[$componentName] : array();
		return $type ? $license[$type] : $license;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getRequestComponent() {
		return $this->inputs['component'] ? $this->inputs['component'] : array('name' => self::EDD_PRODUCT_NAME);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $inputs
	*/
	public function setInputs($inputs) {
		$this->inputs = $inputs;	
	}
	
} // End class.