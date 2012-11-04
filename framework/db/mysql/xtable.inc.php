<?php
/**
* 
*/

/**
* 
*/
abstract class CJTxTable {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $fields;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $item;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $key;
	
	/**
	 * 
	 */
	private $name;
	
	/**
	* put your comment there...
	* 
	* @param mixed $table
	* @return CJTxTable
	*/
	public function __construct($dbDriver, $table, $key = array('id')) {
		$this->dbDriver = $dbDriver;
		$this->name = "#__cjtoolbox_{$table}";
		$this->key = $key;
		// Read table fields.
		$this->fields = $this->dbDriver->getColumns($this->table());
	}
	
	/**
	* DELETE!
	* 
	*/
	public function delete() {
		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $field
	*/
	public function get($field) {
		return $this->item->{$field};
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function &getData() {
		return $this->item;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $object
	* @param mixed $dbDriver
	*/
	public static function getInstance($type, $dbDriver = null, $query = null) {
		$dbDriver = !$dbDriver ? cssJSToolbox::getInstance()->getDBDriver() : $dbDriver;
		// Import table file.
		cssJSToolbox::import("tables:{$type}.php");
		// Get class name.
		$type = str_replace(' ', '', ucfirst(str_replace(array('-', '_'), ' ', $type)));
		$className = "CJT{$type}Table";
		$table = new $className($dbDriver);
		if ($query) {
			$table->load($query);
		}
		return $table;
	}
	
	/**
	* Load record into table!
	* 	
	* @param mixed 
	*/
	public function load($query = null) {
		if (!$query) {
			$item = (array) $this->item;
			$query['select'] = 'SELECT *';
			$query['from'] = "FROM {$this->table()}";
			// Where clause.
			$keys = array_intersect_key($item, array_flip($this->key));
			$query['where'] = 'WHERE ' . implode(' AND ', $this->prepareQueryParameters($keys));
			// Read DB  record!
			$query = "{$query['select']} {$query['from']} {$query['where']}";
		}
		$this->item = array_shift($this->dbDriver->select($query));
		return $this;
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
	
	/**
	* UPDATE/INSERT
	* 
	*/
	public function save() {
		$keyFieldName = $this->key[0];
		$id = $this->item->{$keyFieldName};
		$item = (array) $this->item;
		// Don't update id field.
		$fieldsList = array_diff_key($item, array_flip($this->key));
		$fieldsList = implode(',', $this->prepareQueryParameters($fieldsList));
		if ($id) { // Update
			// Get only key fields.
			$keys = array_intersect_key($item, array_flip($this->key));
			$condition = implode(' AND ', $this->prepareQueryParameters($keys));
			$query = "UPDATE {$this->table()} SET {$fieldsList} WHERE {$condition}";
			$this->dbDriver->update($query);
			$this->dbDriver->processQueue();
		}
		else { // Insert.
			$query = "INSERT {$this->table()} SET {$fieldsList}";
			$this->dbDriver->insert($query);
			$this->dbDriver->processQueue();
			$this->item->{$keyFieldName} = $this->dbDriver->getInsertId();
		}
		 return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $prop
	* @param mixed $value
	*/
	public function set($prop, $value) {
		$this->item->{$prop} = $value;
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function setData($data) {
		// Cast to array.
		$data = (array) $data;
		if (is_null($this->item)) {
			$item = (object) array();
		}
		// Copy values!
		foreach ($data as $name => $value) {
			if ($value !== null) {
				$this->set($name, $value);
			}
		}
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function table() {
		return $this->name;
	}
	
} // End class.
