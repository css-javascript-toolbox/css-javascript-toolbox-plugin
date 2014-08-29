<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTMYSQLQueueDriver extends CJTHookableClass {
	
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
	protected $onexec = array('parameters' => array('param'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onprocessqueue = array('parameters' => array('queue'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onprocesscommand = array('parameters' => array('command'));
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onqueue = array('parameters' => array('query'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onqueuereturn = array('parameters' => array('driver'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onselect = array('parameters' => array('param'));
			
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
		// Hookable!
		parent::__construct();
		// Internal DB engine!
		$this->wpdb = $mysqlDriver;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	protected function addQueue($query) {
		$query = $this->resolveTableName($query);
		$key = md5($query);
		if ($query = $this->onqueue($query)) {
			$this->queue[$key] = $query;	
		}
		return $this->onqueuereturn($this);
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
  	return $this;
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
		# MYSQLI doesn't has numeric field.
		if ($this->wpdb->use_mysqli) {
			$field->numeric = $field->flags & MYSQLI_NUM_FLAG;
		}
		# Check if numeric
		switch ($field->numeric) {
		  case 0:
			  $data = esc_sql($data);
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
	* @param mixed $query
	*/
	public function exec($query) {
		// Initialize!
		$query = $this->resolveTableName($query);
		$resultSet = array();
		// Filtering!
		extract($this->onexec(compact('query', 'resultSet')));
		// filter can controller the returned value or customize the query!
		if ($query && empty($result)) {
			$result = $this->wpdb->query($query);
		}
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $table
	*/
	public function getColumns($table) {
		$columns = array();
		$this->select("SELECT * FROM {$table} WHERE 1!=1 LIMIT 0,1;");
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
	public function getInsertId () {
		return $this->wpdb->insert_id;		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	* @param mixed $returnType
	* @param mixed $default
	*/
	public function getRow($query, $returnType = OBJECT_K, $default = null) {
		$row = null;
		// Fetch al result set!
		$result = $this->select($query, $returnType);
		if ($result) {
			$row = reset($result);
		}
		return $row;
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
		$queue = $this->onprocessqueue($this->queue);
		// Collect queue commands.
		foreach ($queue as $command) {
			$this->wpdb->query($this->onprocesscommand($command));
		}
		// Clear queue.
		$this->clear();
		// Chain.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	public function resolveTableName($query) {
		// Define list of Prefixes to be replaced with Wordpress table prefix!
		$keywords = array('#__wordpress_' => '', '#__cjtoolbox_' => 'cjtoolbox_');
		// Replace each keyword with Wordpress table prefix
		// + if there is any prefix defined for the keyword itself!
		foreach ($keywords as $search => $prefix) {
			$query = str_replace($search, "{$this->wpdb->prefix}{$prefix}", $query);
		}
		// Return new query with table names resolved.
		return  $query;
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function rollback() {
		$this->addQueue('ROLLBACK;');
		return $this;
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
		// Initialize!
		$query = $this->resolveTableName($query);
		$resultSet = array();
		// Filtering!
		extract($this->onselect(compact('query', 'resultSet')));
		// filter can controller the returned value or customize the query!
		if ($query && empty($resultSet)) {
			$resultSet = $this->wpdb->get_results($query, $returnType);
		}
		return $resultSet;
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function startTransaction() {
		$this->addQueue('BEGIN WORK;');
    return $this;
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

// Hooking!
CJTMYSQLQueueDriver::define('CJTMYSQLQueueDriver', array('hookType' => CJTWordpressEvents::HOOK_FILTER));