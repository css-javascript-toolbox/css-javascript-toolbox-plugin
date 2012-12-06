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
class CJTEEWordpressHookAction extends CJTEEWordpressHook {
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	*/
	public function callIndirect($params) {
		$params = parent::prepareHookParameters($params);
		// Do Wordpress action!
		call_user_func_array('do_action', $params);
		// Return subject object!
		return $this;
	}
	
} // End class.