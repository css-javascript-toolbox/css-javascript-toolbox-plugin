<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTemplatesManagerModel {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		//----------print_r($_GET);
		// Build query.
    $query = 'SELECT a.name, t.* ' . $this->getItemsQuery();
		// Build query filter.
		$where   = '';
		$order = '';
		// Execute our query using MYSQL queue driver.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getItemsQuery() {
		// Query all templates.
		$query = "FROM #__cjtoolbox_templates t
													LEFT JOIN #__cjtoolbox_authors a
													ON t.author = a.guid";
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsTotal() {
		$query = 'SELECT count(*) Total ' . $this->getItemsQuery();
		// Get items total.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return array_shift($result)->Total;
	}
	
} // End class.