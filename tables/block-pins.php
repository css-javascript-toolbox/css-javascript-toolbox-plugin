<?php
/**
* 
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTBlockPinsTable extends CJTTable {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct(&$dbDriver) {
		// Inirialize parent class.
		parent::__construct($dbDriver, cssJSToolbox::$config->database->tables->blockPins, 'blockId');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	*/
	public function delete($blocks = array()) {
		$where = '';
		$blocks = (array) $blocks; // $blocks may be passed as single integer.
		if (!empty($blocks)) {
			$where = ' WHERE `blockId` IN (' . implode(',', ((array) $blocks)) . ')';
		}
		$query = "DELETE FROM {$this->table}{$where};";
		$this->dbDriver->delete($query);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	* @param mixed $condition
	*/
	public function get($ids = array(), $condition = null) {
		// Initialize.
		$where = array();
		// May be single integer.
		$ids = (array) $ids;
		// Retrieve specific list of IDs.
		if (!empty($ids)) {
			$ids = implode(',', $ids);
			$where[] = "`blockId` IN ({$ids})";
		}
		// Get CONDITIONS expression.
		if ($condition !== null) {
			$where = array_merge($where, $this->prepareQueryParameters($condition));
		}
		// Use WHERE CLAUSE only if there is Ids or Condition specified.
		$where = !empty($where) ? (' WHERE ' . implode(' AND ', $where)) : '';
		// Get result.
		$query = "SELECT * FROM {$this->table}{$where};";
		return $this->dbDriver->select($query, OBJECT);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @param mixed $pins
	*/
	public function insert($blockId, $pins) {
		// Initialize.
		$rows = array();
		foreach (((array) $pins) as $pin => $values) {
			foreach ($values as $value) {
				$rows[] = "({$blockId},'{$pin}', {$value})";
			}
		}
		// Execute only if there is at least one row to insert.
		if (!empty($rows)) {
			$rows = implode(',', $rows);
			$query = "INSERT INTO {$this->table} (`blockId`, `pin`, `value`) VALUES {$rows};";
			$this->dbDriver->insert($query);
		}
		// Chaining.
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @param mixed $pins
	*/
	public function insertRaw($blockId, $pins) {
		$rows = array();
		foreach ($pins as $pin) {
			$rows[] = "({$blockId},'{$pin->pin}', {$pin->value})";
		}
		$rows = implode(',', $rows);
		$query = "INSERT INTO {$this->table} (`blockId`, `pin`, `value`) VALUES {$rows};";
		$this->dbDriver->insert($query);		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function update($blockId, $pins) {
		// Delete old block pins.
		$this->delete($blockId);
		// Add new block pins.
		$this->insert($blockId, $pins);
	}
	
} // End class.