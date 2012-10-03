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
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($name, $value, $id = null, $classesList = '') {
		return new CJTAuthorsField($name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$query = 'SELECT a.name
												FROM #__cjtoolbox_authors a ORDER BY name';
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$this->items = $dbDriver->select($query);
	}
	
} // End class.