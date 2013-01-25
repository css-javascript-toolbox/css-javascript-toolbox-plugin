<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTErrorsModel {
	
	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsQuery() {
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function isProduction() {
		return (CJTOOLBOX_ACTIVE_PROFILE == CJTOOLBOX_PROFILE_PRODUCTION);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $number
	* @param mixed $message
	* @param mixed $file
	* @param mixed $line
	* @param mixed $contex
	*/
	public function log($number, $message, $file, $line, $contex) {
		
	}
	
} // End class.
