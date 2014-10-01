<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTxTable extends CJTHookableClass {
	
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
	* @var mixed
	*/
	protected $onconcatquery = array('parameters' => array('query'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ondelete = array('parameters' => array('query', 'key'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetdata = array('parameters' => array('item'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetfield = array('parameters' => array('value', 'name'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onimport = array('parameters' => array('type'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $oninstantiate = array('parameters' => array('args'));
		
	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $oninsert = array('parameters' => array('query'));

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $oninserted  = array('hookType' => CJTWordpressEvents::HOOK_ACTION);
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloadquery  = array('parameters' => array('query'));
	
	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $onsetfield = array('parameters' => array('property'));
	
	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $onupdate = array('parameters' => array('query'));

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $onupdated = array('hookType' => CJTWordpressEvents::HOOK_ACTION);
	
	/**
	* put your comment there...
	* 
	* @param mixed $table
	* @return CJTxTable
	*/
	public function __construct($dbDriver, $table, $key = array('id')) {
		// Hookable!
		parent::__construct();
		// Reset intenal item object!
		$this->setItem();
		// Initialize!
		$this->dbDriver =$dbDriver;
		$this->name = "#__cjtoolbox_{$table}";
		$this->key = $key;
		// Read table fields.
		$this->fields = $this->dbDriver->getColumns($this->table());
	}
	
	/**
	* DELETE!
	* 
	* THIS METHOD SUPPORT COMPOUND KEYS!
	* 
	*/
	public function delete($key = null) {
		// building DELETE query!
		$query['from']  = "DELETE FROM {$this->table()}";
		$query['where'] = 'WHERE ' . implode(' AND ', $this->prepareQueryParameters($key = $this->getKey($key)));
		// filtering!
		if ($query = $this->ondelete($query, $key)) {
			$query = "{$query['from']} {$query['where']}";
			// Delete record.
			$this->dbDriver->delete($query)->processQueue();			
		}
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function fetchAll() {
		// Get query.
		$query = $this->getLoadQuery();
		// Return all result.
		return $this->dbDriver->select($query, ARRAY_A);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $field
	*/
	public function get($field) {
		// Get the value of the field with E_ALL complain!
		$value = (is_object($this->item) && property_exists($this->item, $field)) ? $this->item->{$field} : null;
		return $this->ongetfield($value, $field);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getData() {
		return $this->ongetdata($this->item);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $object
	* @param mixed $dbDriver
	* @return CJTxTable
	*/
	public static function getInstance($type, $dbDriver = null, $query = null) {
		// Filter all parameters.
		extract(self::trigger('CJTxTable.instantiate', compact('type', 'dbDriver', 'query')));
		// Getting dbdriver!
		$dbDriver = !$dbDriver ? cssJSToolbox::getInstance()->getDBDriver() : $dbDriver;
		// Import table file.
		self::import($type);
		// Get class name.
		$type = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $type)));
		$className = "CJT{$type}Table";
		$table = new $className($dbDriver);
		if ($query) {
			$table->load($query);
		}
		return $table;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	protected function getLoadQuery($query = null) {
		$tableKey = null;
		$key = null;
		// Query might be an array of keys!
		if (is_array($query)) {
			$tableKey = $query;
			$query = null;
		}
		if (!$query) {
			$item = (array) $this->item;
			$query['select'] = 'SELECT *';
			$query['from'] = "FROM {$this->table()}";
			// Load only if key is not NULL!
			if ($key = $this->isValidKey($tableKey)) {
				$query['where'] = 'WHERE ' . implode(' AND ', $this->prepareQueryParameters($key));
				if ($query = $this->onconcatquery($query)) {
					// Read DB  record!
					$query = "{$query['select']} {$query['from']} {$query['where']}";
				}
			}
			else {
				throw new CJT_Framework_DB_Mysql_Driver_Exception_Invalidkey($key);
			}
		}
		return $query;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $tableKey
	*/
	public function getKey($tableKey = null) {
		if (!$tableKey) {
			$tableKey = $this->getTableKey();
		}
		$key = array_intersect_key(((array) $this->item), array_flip($tableKey));
		return $key;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getTableKey() {
		return $this->key;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed
	*/
	public static function import($type) {
		// Filtering parameters!
		extract(self::trigger('CJTxTable.import', compact('type')));
		// Implort table file.
		cssJSToolbox::import("tables:{$type}.php");
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tableKey
	*/
	public function isValidKey($tableKey = null) { 
		$isValid = false;
		// Get key!
		$key = $this->getKey($tableKey);
		// If any field has a value then its not null key!
		foreach ($key as $field) {
			if ($field !== null) {
				$isValid = $key;
				break;
			}
		}
		return $isValid;
	}
	


	/**
	* Load record into table!
	* 	
	* @param mixed 
	*/
	public function load($query = null) {
		try {
			// Get query to load!
			$query = $this->getLoadQuery($query);
			// Set the returned item.
			$this->setItem($this->dbDriver->getRow($this->onloadquery($query)));
		}
		catch (CJT_Framework_DB_Mysql_Driver_Exception_Invalidkey $exception) {
			// Backword compatibility to don't show any error.
			// This is how the old code procedure was!
		}
		// Chaining.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $values
	*/
	public function loadAsKey($values) {
		return $this->setData($values)->load(array_keys($values));
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
	* THIS METHOD STILL DOESNT SUPPORT COMPOUND KEYS!!
	* 
	* @param mixed $forceInsert
	* @param mixed $updateIdField
	* @return CJTxTable
	*/
	public function save($forceInsert = false, $updateIdField = false) {
		$keyFieldName = $this->key[0];
		$item = (array) $this->item;
		// Don't update id field unless $updateIdField is set to TRUE.
		$fieldsList = !$updateIdField ? array_diff_key($item, array_flip($this->key)) : $item;
		$fieldsList = implode(',', $this->prepareQueryParameters($fieldsList));
		if (!$forceInsert && $this->get($keyFieldName)) { // Update
			// Where clause.
			$condition = implode(' AND ', $this->prepareQueryParameters($this->getKey()));
			$query = "UPDATE {$this->table()} SET {$fieldsList} WHERE {$condition}";
			if ($query = $this->onupdate($query)) {
				$this->dbDriver->update($query)
																					->processQueue();
				$this->onupdated();
			}
		}
		else { // Insert.
			$query = "INSERT {$this->table()} SET {$fieldsList}";
			if ($query = $this->oninsert($query)) {
				$this->dbDriver->insert($query)
																					->processQueue();
				$this->item->{$keyFieldName} = $this->dbDriver->getInsertId();
				$this->oninserted();
			}
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
		extract($this->onsetfield(compact('prop', 'value')));
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
	* @param mixed $item
	* @return CJTxTable
	*/
	public function setItem($item = null) {
		// If NULL create empty stdClass, if array cast to stdClass!
		if (!is_object($item)) {
			$item = is_array($item) ? (object) $item : new stdClass();
		}
		// Set internal item object!
		$this->item = $item;
		// Chaining!
		return $this;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $key
	*/
	public function setTableKey($key) {
		$this->key = $key;
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

// Hookable.
CJTxTable::define('CJTxTable', array('hookType' =>CJTWordpressEvents::HOOK_FILTER));
