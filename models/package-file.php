<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackageFileModel extends CJTHookableClass {
	
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
CJTPackageFileModel::define('CJTPackageFileModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));