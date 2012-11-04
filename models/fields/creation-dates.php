<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTCreationDatesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '', $moreIntoTag = null) {
		// Initialize parent.
		parent::__construct($form, $name, $value, $id, $classesList, 'creationDate', null, $moreIntoTag);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($form, $name, $value, $id = null, $classesList = '', $moreIntoTag = null) {
		return new CJTCreationDatesField($form, $name, $value, $id, $classesList, $moreIntoTag)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		// Query CJT Authors + Wordpress build-in local users.
		$query = ' SELECT DISTINCT(creationDate) FROM #__cjtoolbox_templates ORDER BY creationDate;';
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		// Add no selection author!
		$this->items['']['creationDate'] = '---  ' . cssJSToolbox::getText('Creation Date') . '  ---';
		// Get all exists authors
		$this->items += $dbDriver->select($query);
	}
	
} // End class.