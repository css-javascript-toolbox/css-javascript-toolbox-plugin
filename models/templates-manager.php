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
	
	/** */
	const FLAG_LAST_REVISION = 0x80000000;
	
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
		$select = 'SELECT t.id, 
																					t.name, 
																					t.type, 
																					t.description, 
																					t.creationDate, 
																					r.dateCreated lastModified,
																					t.state, 
																					a.name author,
																					r.version,
																					r.state developmentState';
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
    $query = "{$select}{$queryBase['from']}{$queryBase['where']}{$queryBase['groupBy']}{$orderBy}{$limit}";
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
		return 20;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getItemsQuery() {
		// From clause.
		$query['from'] = ' FROM #__cjtoolbox_templates t
													LEFT JOIN #__cjtoolbox_template_revisions r ON t.id = r.templateId
													LEFT JOIN #__cjtoolbox_authors a ON t.authorId = a.id';
		// Always get only the last revision.
		$where[] = '(r.attributes & ' . self::FLAG_LAST_REVISION . ')';
		// Build where clause based on the given filters!
		$filters = array(
			'Templatetypes' => array('table' => 't', 'name' =>'type'), 
			'Authors' => array('table' => 't', 'name' => 'authorId'),
			'Versions' => array('table' => 'r', 'name' => 'version'),
			'Creationdates' => array('table' => 't', 'name' => 'creationDate'),
			'States' => array('table' => 't', 'name' => 'state'),
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
		$query = "{$select}{$queryBase['from']}{$queryBase['where']}{$queryBase['groupBy']}";
		// Get items total.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return array_shift($result)->Total;
	}
	
} // End class.