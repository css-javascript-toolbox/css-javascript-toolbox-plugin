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
class CJTTemplatesListSQLView extends CJTSQLView {

	/**
	* 
	* Initialize new object.
	* 
	* @return void
	*/
	public function __construct($driver) {
		// Initialize base.
		parent::__construct($driver);
	}
	
	/**
	* 
	* 
	*/
	public function __toString() {
		// Set tables name.
		$tables['authors'] = $this->driver->getTableName(cssJSToolbox::$config->database->tables->authors);
		$tables['templates'] = $this->driver->getTableName(cssJSToolbox::$config->database->tables->templates);
		// Build View.
		$query['from'] = "{$tables['templates']} tmpls LEFT JOIN {$tables['authors']} authors
											ON tmpls.authorId = authors.id";
		// Build where clause.
		$query['where'] = $this->driver->prepareQueryParameters($this->query->filter);
		// Combine all into one statement.
		$query = $this->buildQuery($query['from'], $query['where']);
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function exec() {
		return $this->driver->select($this, OBJECT_K);
	}
	
} // End class.