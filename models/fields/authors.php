<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTAuthorsField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '', $moreIntoTag = null) {
		// Initialize parent.
		parent::__construct($form, $name, $value, $id, $classesList, 'name', 'id', $moreIntoTag);
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
		return new CJTAuthorsField($form, $name, $value, $id, $classesList, $moreIntoTag)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		// Query CJT Authors + Wordpress build-in local users.
		$query = ' SELECT a.id, a.name 
													FROM #__cjtoolbox_authors a
													RIGHT JOIN #__cjtoolbox_templates t
													ON a.id = t.authorId';
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		// Add no selection author!
		$this->items['']['name'] = '---  ' . cssJSToolbox::getText('Author') . '  ---';
		// Get all exists authors
		$this->items += $dbDriver->select($query);
		// Sort items.
		asort($this->items);
	}
	
} // End class.