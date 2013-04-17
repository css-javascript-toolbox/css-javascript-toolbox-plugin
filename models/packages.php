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
	public function getItems($page) {
		// Build query.
		$select = 'SELECT p.id, p.name,  p.author,  p.uri';
		$queryBase = $this->getItemsQuery();
		// Paging.
		$itemsPerPage = $this->getItemsPerPage();
		// Get page no#.
		if (!$page) {
			$page = 1;
		}
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
		// Import dependencies.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		CJTxTable::import('template-revision');
		CJTxTable::import('author');
		// From clause.
		$query['from'] = ' FROM #__cjtoolbox_packages p
													LEFT JOIN #__cjtoolbox_package_objects o ON p.id = o.packageId';
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
		return array_shift($result)->Total;
	}
	
} // End class.