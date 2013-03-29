<?php
/**
* 
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Import helpers that may be used by models.
*/
require_once CJTOOLBOX_INCLUDE_PATH . '/db/mysql/single-table-simple-view.inc.php';

/**
* 
*/
abstract class CJTTable {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $fields = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $table = '';
	
	/**
	* put your comment there...
	* 
	* @var CJTMYSQLQueueDriver
	*/
	public function __construct(&$dbDriver, $table) {
		// Set table name.
		$this->dbDriver = $dbDriver;
		$this->table = $this->dbDriver->getTableName($table);
		// Read table fields.
		$this->fields = $this->dbDriver->getColumns($this->table);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getDBDriver() {
		return $this->dbDriver;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getFields() {
		return $this->fields;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->table;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getNextId($offset = 0) {
		$query = "SHOW TABLE STATUS
							LIKE '{$this->table}';";
		$newId = $this->dbDriver->row($query)->Auto_increment;
		// Add offset.
		$newId = $newId + $offset;
		return $newId;
	}
	
	/**
	* put your comment there...
	* 
	* @todo Delete method and use CJTMYSQLQuery instead.
	* 
	* @param mixed $parameters
	*/
	protected function prepareQueryParameters($parameters, $operators = array(), $defaultOperator = '=', $excludeNulls = true) {
		$prepared = array();
		// For every parameter esacape name value.
		foreach ($parameters as $name => $value) {
			if (!$excludeNulls || ($value !== null)) {
				if (array_key_exists($name, $this->fields) === FALSE) {
					throw new Exception("Field:{$name} is not found!");
				}
				else {
				  $field = $this->fields[$name];
				  // Escape field name and value.
				  $value = $this->dbDriver->escapeValue($value, $field);
				  // Get name-value operator.
				  $operator = isset($operators[$name]) ? $operators[$name] : $defaultOperator;
				  $prepared[] = "`{$name}`{$operator}{$value}";
				}			
			}
		}
		return $prepared;
	}

	
} // End class.