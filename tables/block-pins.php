<?php
/**
* 
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

// CJTTable class.
require_once CJTOOLBOX_FRAMEWORK . '/db/mysql/table.inc.php';

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
	*/
	public function get($ids = array()) {
		$where = '';
		$ids = (array) $ids; // May be single integer.
		if (!empty($ids)) {
			$ids = implode(',', $ids);
			$where = " WHERE `blockId` IN ({$ids})";
		}
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
		$rows = array();
		if (!empty($pins)) {
			foreach ($pins as $pin => $values) {
				foreach ($values as $value) {
					$rows[] = "({$blockId},'{$pin}', {$value})";
				}
			}
			$rows = implode(',', $rows);
			$query = "INSERT INTO {$this->table} (`blockId`, `pin`, `value`) VALUES {$rows};";
			$this->dbDriver->insert($query);
		}
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