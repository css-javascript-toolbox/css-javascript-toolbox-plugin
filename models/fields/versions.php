<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTVersionsField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '', $moreIntoTag = null) {
		// Initialize parent.
		parent::__construct($form, $name, $value, $id, $classesList, 'version', null, $moreIntoTag);
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
		return new CJTVersionsField($form, $name, $value, $id, $classesList, $moreIntoTag);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$lastVersionFlag = 0x80000000;
		// Query CJT Authors + Wordpress build-in local users.
		$query = "SELECT DISTINCT(version) 
												FROM #__cjtoolbox_template_revisions
												WHERE (attributes & {$lastVersionFlag})
												ORDER BY version;";
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		// Add no selection author!
		$this->items['']['version'] = '---  ' . cssJSToolbox::getText('Version') . '  ---';
		// Get all exists authors
		$this->items += $dbDriver->select($query);
	}
	
} // End class.