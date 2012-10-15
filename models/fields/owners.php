<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTOwnersField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '') {
		// Initialize parent.
		parent::__construct($form, $name, $value, $id, $classesList, 'user_login', 'ID');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($form, $name, $value, $id = null, $classesList = '') {
		return new CJTOwnersField($form, $name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		// Add no selection author!
		$this->items['']['user_login'] = '---  ' . cssJSToolbox::getText('Owner') . '  ---';
		// Get all exists authors
		$this->items += get_users();
		// Sort items.
		asort($this->items);
	}
	
} // End class.