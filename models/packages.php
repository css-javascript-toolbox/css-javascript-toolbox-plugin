<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackagesModel {

	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		// Build query.
		$select = 'SELECT p.id, p.name, p.description, p.author,  p.webSite, p.license, p.readme';
		$queryBase = $this->getItemsQuery();
		// Paging.
		$itemsPerPage = $this->getItemsPerPage();
		// Get page no#.
		$page = !isset($_REQUEST['paged']) ? 1 : $_REQUEST['paged'];
		// Calculate start offset.
		$start = ($page - 1) * $itemsPerPage;
		$limit = " LIMIT {$start},{$itemsPerPage}";	
		// final query.
    $query = "{$select}{$queryBase['from']}{$limit}";
		// Execute our query using MYSQL queue driver.
		$result = cssJSToolbox::getInstance()->getDBDriver()->select($query);
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsPerPage() {
		return 20;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsQuery() {
		// From clause.
		$query['from'] = ' FROM #__cjtoolbox_packages p';
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsTotal() {
		$queryBase = $this->getItemsQuery();
		$select = 'SELECT count(*) Total';
		$query = "{$select}{$queryBase['from']}";
		// Get items total.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return reset($result)->Total;
	}
	
} // End class.