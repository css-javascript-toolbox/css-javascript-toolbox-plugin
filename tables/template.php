<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplateTable {
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTTemplatesTable
	*/
	public function __construct($dbDriver) {
		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function fill($data) {
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getStdObject() {
		return (object) get_object_vars($this);
	}
	
	/**
	* put your comment there...
	* 	
	* @param mixed $guid
	*/
	public function load($guid) {
		$item = array('name' => 'NAMW!~!~', 'description' => 'DESC');
		foreach ($item as $name => $value) {
			$this->{$name} = $value;
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		
	}
	
	/**
	* put your comment there...
	* 	
	*/
	public function validate() {
		
	}
	
} // End class.