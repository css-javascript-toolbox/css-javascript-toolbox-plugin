<?php
/**
* 
*/

/**
* 
*/

class CJTMYSQLQueueDriver {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $queue = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $wpdb = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($mysqlDriver) {
		$this->wpdb = $mysqlDriver;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	protected function addQueue($query) {
		$key = md5($query);
		$this->queue[$key] = $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function clear() {
		$this->queue = array();
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function commit() {
		$this->addQueue('COMMIT;');
  	return;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	public function delete($query) {
		return $this->addQueue($query);
	}
	
	/**
	* put your comment there...
	* 
	* @param string $data
	* @param mixed $field
	* @return string
	*/
	public function escapeValue($data, $field) {
		switch ($field->numeric) {
		  case 0:
			  $data = mysql_real_escape_string($data, $this->wpdb->dbh);
			  $data = "'{$data}'";
		  break;
		  case 1:
		  	$data = (int) $data;
		  break;
		}
		return $data;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $table
	*/
	public function getColumns($table) {
		$columns = array();
		$this->wpdb->query("SELECT * FROM {$table} WHERE 1!=1;");
		// Use field name as element key.
		foreach ($this->wpdb->col_info as $index => $column) {
			$columns[$column->name] = $column;
		}
		return $columns;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getTableName($table) {
		return "{$this->wpdb->prefix}{$table}";
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getVar($query, $row = 0, $column = 0) {
		return $this->wpdb->get_var($query, $row, $column);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	public function insert($query) {
	  return $this->addQueue($query);
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function merge($driver) {
		// Put target driver queue at the end of our queue.
		$this->queue = array_merge($this->queue, $driver->queue);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $parameters
	* @param mixed $operators
	* @param mixed $defaultOperator
	* @param mixed $excludeNulls
	*/
	public function prepareQueryParameters($parameters, $operators = array(), $defaultOperator = '=', $excludeNulls = true) {
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
				  $value = $this->escapeValue($value, $field);
				  // Get name-value operator.
				  $operator = isset($operators[$name]) ? $operators[$name] : $defaultOperator;
				  $prepared[] = "`{$name}`{$operator}{$value}";
				}			
			}
		}
		return $prepared;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function processQueue() {
		// Collect queue commands.
		foreach ($this->queue as $command) {
			  $this->wpdb->query($command);
		}
		// Clear queue.
		$this->clear();
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function rollback() {
		$this->addQueue('ROLLBACK;');
		return;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function row($query) {
		return $this->wpdb->get_row($query);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	public function select($query, $returnType = OBJECT_K) {
		// Resolve tabel names.
		$query = str_replace('#__', 'cjtv6_', $query);
		return $this->wpdb->get_results($query, $returnType);
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function startTransaction() {
		$this->addQueue('BEGIN WORK;');
    return;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	public function update($query) {
		return $this->addQueue($query);
	}
	
} // End class.