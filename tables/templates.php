<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
cssJSToolbox::import('framework:db:mysql:table.inc.php');

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesTable extends CJTTable {

	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTTemplatesTable
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, cssJSToolbox::$config->database->tables->templates);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $key
	*/
	public function delete($key) {
		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $filter
	* 	- key: Template row key.
	* 	- types: Filter by types.
	* 
	* @param mixed $order
	* @param mixed $limits
	* @return
	*/
	public function get($columns = array('*'), $filter = array(), $order = array(), $limits = array()) {
		$query = new CJTMYSQLQuery($this);
		// Set query parameters.
		$queryParameters =& $query->getQueryObject();
		$queryParameters->columns = $columns;
		$queryParameters->filter = $filter;
		$queryParameters->orderBy = $order;
		$queryParameters->limits = $limits;
		// Execute and return the result.
		$templates = $this->dbDriver->select((string) $query);
		return $templates;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $template
	*/
	public function insert($template) {
		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $template
	*/
	public function update($template) {
		
	}
	
} // End class.