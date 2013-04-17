<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackageModel extends CJTHookableClass {
	
	/**
	* put your comment there...
	* 
	*/
	public function getItem() {}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {}
	
} // End class.

// Hookable!
CJTPackageModel::define('CJTPackageModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));