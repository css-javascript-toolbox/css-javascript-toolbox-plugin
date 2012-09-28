<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Import libs.
*/
require_once CJTOOLBOX_INCLUDE_PATH . '/db/mysql/sql-view.inc.php';

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTMYSQLQuery extends CJTSQLView {
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Build where clause.
		$query['where'] = implode(' AND ', $this->driver->getDBDriver()->prepareQueryParameters($this->query->filter));
		// Build query.
		$query = $this->buildQuery($this->driver->getName(), $query['where']);
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function exec() {
		return $this->driver->dbDriver->select($this, OBJECT_K);
	}
	
} // End class.