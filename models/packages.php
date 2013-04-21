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
	* @var mixed
	*/
	protected $params = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	* @return CJTPackagesModel
	*/
	public function __construct($params = array()) {
		$this->params = $params;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $method
	* @return CJTPackagesModel
	*/
	public function call($method) {
		// Get old params copy.
		$tParams = $this->params;
		// Get passed parameters.
		$params = func_get_args();
		array_shift($params); // Remove method name.
		// Store parameters is private store!
		$this->params = $params;
		// Call method.
		$result = $this->{$method}();
		// Clear store.
		$this->params = $params;
		// Return method call returned value.
		return $result;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		// Build query.
		$select = 'SELECT p.id, p.name, p.description,  p.author, p.authorMail,  p.uri';
		$queryBase = $this->getItemsQuery();
		// Paging.
		$itemsPerPage = $this->getItemsPerPage();
		// Get page no#.
		if (!$page = $this->getParam('paged')) {
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
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getParam($name) {
		 return isset($this->params[$name]) ? $this->params[$name] : null;
	}
	
} // End class.