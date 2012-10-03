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
class CJTAuthorsTable extends CJTTable {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, cssJSToolbox::$config->database->tables->authors);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $key
	*/
	public function delete($guid = null) {
		// Where clause filters.
		$where = '';
		if ($guid) {
		  $where = " WHERE guid = '{$key}'";
		}
		// Delete authors.
		$query = "DELETE FROM {$this->table}{$where}";
		$this->dbDriver->delete($query);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public function get($guid) {
		// Select all backups for specific backup type.
		$query = "SELECT id, name, owner, created
							FROM {$this->table}
							WHERE type = '{$type}'";
		// Use id field as array key.
		$backups = $this->dbDriver->select($query, OBJECT);
		return $backups;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $backup
	*/
	public function insert($data) {
		// Prepare insert statement fields.
		$data = $this->prepareQueryParameters($data);
		$data = implode(',', $data);
		// Build insert statement.
		$query = "INSERT INTO {$this->table} SET {$data}";
		$this->dbDriver->insert($query);
	}
	
} // End class.