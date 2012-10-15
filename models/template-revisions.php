<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTemplateRevisionsModel {
	
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
		//print_r($_GET);
		// Build query.
		$select = 'SELECT r.revisionNo,
												r.version, 
												r.guid, 
												r.isTagged,
												r.changeLog,
												r.state,
												o.user_login ownerName,
												a.name authorName,
												a.attributes';
		$queryBase = $this->getItemsQuery();
		// Paging.
		$itemsPerPage = $this->getItemsPerPage();
		// Get page no#.
		$page = !isset($_GET['paged']) ? 1 : $_GET['paged'];
		// Calculate start offset.
		$start = ($page - 1) * $itemsPerPage;
		$limit = " LIMIT {$start},{$itemsPerPage}";
		// Order.
		if (isset($_GET['orderby'])) {
			$orderBy = " ORDER BY {$_GET['orderby']} {$_GET['order']}";
		}
		// final query.
    $query = "{$select}{$queryBase['from']}{$queryBase['where']}{$orderBy}{$limit}";
		// Execute our query using MYSQL queue driver.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsPerPage() {
		return 2;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getItemsQuery() {
		// From clause.
		$query['from'] = ' FROM #__cjtoolbox_template_revisions r
																				LEFT JOIN 
																				(SELECT name, attributes, guid FROM #__cjtoolbox_authors 
																				UNION 
																				SELECT CONCAT("Local (", user_login, ")") name, 2 attributes, id guid from #__users) a
																				ON r.author = a.guid
																				LEFT JOIN #__users o ON r.owner = o.id';
		// ALWAYS Query only revisions for the requested Template GUID.
		$where[] = "r.guid = '{$_REQUEST['guid']}'";
		// Build where clause based on the given filters!
		$filters = array(
			'Authors' => array('table' => 'a', 'name' => 'author'),
			'States' => array('table' => 'r', 'name' =>'state'), 
			'Owners' => array('table' => 'r', 'name' => 'owner'),
			'Releases' => array('table' => 'r', 'name' => 'isTagged'),
		);
		foreach ($filters as $name => $field) {
			$filterName = "filter_{$name}";
			// Add filter only if there is a value specified.
			if (!empty($_REQUEST[$filterName])) {
				$where[] = "{$field['table']}.{$field['name']} = '{$_REQUEST[$filterName]}' ";
			}
		}
		$query['where'] = ' WHERE ' .  implode(' AND ', $where);	
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsTotal() {
		$queryBase = $this->getItemsQuery();
		$select = 'SELECT count(*) Total';
		$query = "{$select}{$queryBase['from']}{$queryBase['where']}";
		// Get items total.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return array_shift($result)->Total;
	}
	
} // End class.