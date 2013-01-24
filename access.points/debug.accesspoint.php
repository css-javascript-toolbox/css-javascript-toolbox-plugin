<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Enable automatic upgrades CJT Plugin
* and all its installed extensions!
* 
* Special type of access point that will do the job and 
* then it'll unload itself!
* 
* @author Ahmed Hamed
*/
class CJTDebugAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'debug';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Don't work unless we're in development state!
		if (CJTOOLBOX_ACTIVE_PROFILE == CJTOOLBOX_PROFILE_DEVELOPMENT) {
			// Show Wordpress Database Error!
			$GLOBALS['wpdb']->show_errors(true);
			// E_ALL Complain! We'll do start from 
			 error_reporting(E_ALL);
		}
	}
	
	/**
	* This access point is to do internal jobs without
	* taking the place of the activate controller that requested
	* by client!
	* 
	* @return Boolean false
	*/
	public function connected() {
		throw new Exception('I\'ll never be the connected object!');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function route() { /* Nothing in here! */}
	
} // End class.

// Hookable!
CJTDebugAccessPoint::define('CJTDebugAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));