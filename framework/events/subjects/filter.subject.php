<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
require_once 'hook.subject.php';

/**
* 
*/
class CJTEEWordpressHookFilter extends CJTEEWordpressHook {
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	*/
	public function callIndirect($params) {
		$params = parent::prepareHookParameters($params);
		// Do Wordpress action!
		return call_user_func_array('apply_filters', $params);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareResultParameters() {
		// Initialize.
		$params =& $this->result['params'];
		// Get first element key
		reset($params);
		$key = key($params);
		// Set the first parameter to the returned value!
		$params[$key] = $this->result['return'];
	}
	
} // End class.