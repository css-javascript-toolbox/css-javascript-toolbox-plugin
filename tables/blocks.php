<?php
/**
* @deprecated
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

// CJTTable class.
require_once CJTOOLBOX_FRAMEWORK . '/db/mysql/table.inc.php';

/**
* 
* @deprecated Use CJTBlockTable instead!
*/
class CJTBlocksTable extends CJTTable {
	
	/** */
	const BLOCK_META_BOX_ID_META_NAME = '__CJT-BLOCK-ID';
	
	/** */
	const BLOCK_META_BOX_STATUS_META_NAME = '__CJT-BLOCK-STATUS';
			
	/**
	* put your comment there...
	* 
	*/
	public function __construct(&$dbDriver) {
		// Inirialize parent class.
		parent::__construct($dbDriver, cssJSToolbox::$config->database->tables->blocks);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	*/
	public function delete($ids = array()) {
		$where = '';
		if (!empty($ids)) {
			$where = ' WHERE `id` IN (' . implode(',', ((array) $ids)) . ')';
		}
		$query = "DELETE FROM {$this->table}{$where};";
		$this->dbDriver->delete($query);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	* @param mixed $fields
	* @param mixed $filters
	* @deprecated @param mixed $returnType
	* @param mixed $orderBy
	*/
	public function get($ids = array(), $fields = array('*'), $filters = array(), $returnType = OBJECT_K, $orderBy = array(), $useDefaultBackupFltr = true) {
		// Warn if $returnTypes is used
		if ($returnType != OBJECT_K) {
			die('Warning! Using $returnType with value other than OBJECT_K from the caller!!!');
		}
		$blocks = array();
		$where = array();
		// Query block ids.
		$ids = (array) $ids; // $ids may be single integer.
		if (!empty($ids)) {
			$ids = implode(',', $ids);
			$where[] = " `id` IN ({$ids})";
		}
		// Filter by backup name.
		if ($useDefaultBackupFltr) {
			$where[] = (!isset($filters['backupId']) ? ' `backupId` IS NULL' : " `backupId` = {$filters['backupId']}");
			unset($filters['backupId']);
		}
		// Filter by parent.
		if (isset($filters['parent'])) {
			$filters['parent'] = implode(',', ((array) $filters['parent']));
			$where[] = " `parent` IN ({$filters['parent']})";
			unset($filters['parent']);
		}
		// Types filter.
		if (isset($filters['types'])) {
			$types = '"' .  implode('", "', $filters['types']) . '"';
			$where[] = " `type` IN ({$types})";
			unset($filters['types']);
		}
		// Do other filters as standard where opertions nothing specific here.
		$where = array_merge($where, $this->prepareQueryParameters($filters));
		// Build where clause if there.
		if (!empty($where)) {
		  $where = ' WHERE ' . implode(' AND ', $where);
		}
		// Order by Clause.
		$orderBy = empty($orderBy) ? '' : (" ORDER BY " . implode(',', $orderBy));
		// Build fields list.
		$fields = implode(',', $fields);
		// Build full query.
		$query = "SELECT {$fields} FROM {$this->table}{$where}{$orderBy};";
		$resultSet = $this->dbDriver->select($query, OBJECT);
		// Use block id as key.
		foreach($resultSet as $block) {
			$blocks[$block->id] = $block;
		}
		return $blocks;
	}
	
	/**
	* Override Parent::getNextId().
	*/
	public function getNextId($offset = 0) {
		// Get post meta table name.
		$postMetaTable = $this->dbDriver->getTableName('postmeta');
		// Get all reserved blocks/metaboxes id associated with posts.
		$postMetaName = self::BLOCK_META_BOX_ID_META_NAME;
		$query = "SELECT meta_value as `value`
							FROM {$postMetaTable}
							WHERE meta_key = '{$postMetaName}';";
		// Get all ids.
		$reservedIds = array_keys($this->dbDriver->select($query, OBJECT_K));			
		// Find a unique Id for the new block.
		do {
			// Increase by one until finding new unique id.
			$nextId = parent::getNextId($offset++);
		} while(in_array($nextId, $reservedIds));
		// Return new id.
		return $nextId;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function insert($data) {
		// Prepare New Record data.
		$data = $this->prepareQueryParameters($data);
		$data = implode(',', $data);
		// Insert statement.
		$query = "INSERT INTO {$this->table} SET {$data};";
		$this->dbDriver->insert($query);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function update($block) {
		// Get block id copy.
		$id = $block['id'];
		// Don't update id field.
		unset($block['id']);
		if (!empty($block)) {
			// Prepare New Record data.
			$block = $this->prepareQueryParameters($block);
			$block = implode(',', $block);
			// Insert statement.
			$query = "UPDATE {$this->table} SET {$block} WHERE `id` = {$id};";
			$this->dbDriver->update($query);
		}
		return $this;
	}
	
} // End class.