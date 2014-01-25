<?php
/**
* @version $ Id; blocks.php 21-03-2012 03:22:10 Ahmed Said $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

// Blocks Database tables.
require_once CJTOOLBOX_TABLES_PATH . '/blocks.php';
require_once CJTOOLBOX_TABLES_PATH . '/block-pins.php';
// MYSQL Queue Driver.
require_once CJTOOLBOX_INCLUDE_PATH . '/db/mysql/queue-driver.inc.php';
		
/**
* Provide simple access (read or write) to all Blocks data.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlocksModel {
	
	/**
	* 
	*/
	const MAX_REVISIONS_PER_BLOCK = 10;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize CTTTable MYSQL Driver.
		$this->dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
	}
		
	/**
	* put your comment there...
	* 
	* @param mixed $data
	* @param mixed $pins
	* @param mixed $customPins
	*/
	public function add($block) {
		// Make sure it array and not stdClass as it has been used from various areas!
		$block = (array) $block;
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		// Get new id if not specified.
		if (!$block['id']) {
			$block['id'] = $blocks->getNextId();
		}
		$blocks->insert($block);
		return $block['id'];
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function addRevision($blockId) {
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		$pins = new CJTBlockPinsTable($this->dbDriver);
		// We allow only up to self::MAX_REVISIONS_PER_BLOCK revisions per block.
		$revisions['fields'] 	= array('id');
		$revisions['filters'] = array('type' => 'revision', 'parent' => $blockId);
		$revisions = $blocks->get(null, $revisions['fields'], $revisions['filters']);
		// If revisions reached self::MAX_REVISIONS_PER_BLOCK delete first one.
		if (count($revisions) == self::MAX_REVISIONS_PER_BLOCK) {
			$this->delete(array_shift($revisions)->id);
		}
		// Get block data.                                                    
		$block['fields'] = array('id', 'lastModified', 'pinPoint', 'code', 'links', 'expressions');
		// get() developed to return multiple blocks, fetch the first.
		$result = $blocks->get($blockId, $block['fields']);
		$block = reset($result);
		// Set other fields.
		$block->location = $block->state = '';
		$block->parent = $blockId;
		$block->type = 'revision';
		$block->created = current_time('mysql');
		$block->owner = get_current_user_id();
		$block->id = $blocks->getNextId(); // Get new id for revision rrecord.
		// Add block data.
		$blocks->insert($block);
		// Get block pins and insert pins for the revision block.
		$blockPins = $pins->get($blockId);
		if (!empty($blockPins)) {
			$pins->insertRaw($block->id, $blockPins);
		}
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function dbDriver() {
  	return $this->dbDriver;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	*/
	public function delete($ids) {
		// Allow single or multiple Ids to be passed.
		if (!is_array($ids)) {
			$ids = array($ids);
		}
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		$pins = new CJTBlockPinsTable($this->dbDriver);
		// Get blocks revisions.
		$revisions['fields'] = array('id');
		$revisions['filters']['parent'] = $ids;
		$revisions['type'] = 'revision';
		$revisions['result'] = $blocks->get(null, $revisions['fields'], $revisions['filters']);
		// Revisions ids used as revision key.
		$revisions = array_keys($revisions['result']);
		// Delete all revisions for all "DELETED" blocks
		// only if there is at least one revision.
		if (!empty($revisions)) {
			$blocks->delete($revisions);
			$pins->delete($revisions);
		}
		// Delete blocks.
		$blocks->delete($ids);
		$pins->delete($ids);
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	* @param mixed $fields
	*/
	public function getBlock($id, $filters = array(), $fields = array('*'), $useDefaultBackupFltr = true) {
		$blocks = $this->getBlocks($id, $filters, $fields, OBJECT_K, array(), $useDefaultBackupFltr);
		$block = !empty($blocks) ? reset($blocks) : null;
		return $block;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	*/
	public function getBlocks($ids = array(), $filters = array(), $fields = array('*'), $returnType = OBJECT_K, $orderBy = array(), $useDefaultBackupFltr = true) {
		$blocks = array();
		// Create Tables objects.
		$blocksTable = new CJTBlocksTable($this->dbDriver);
		$pinsTable = new CJTBlockPinsTable($this->dbDriver);
		// Read blocks.
		$blocks = $blocksTable->get($ids, $fields, $filters, $returnType, $orderBy, $useDefaultBackupFltr);
		// Get only pins for retrieved blocks.
		$ids = array_keys($blocks);
		$pins = empty($ids) ? array() : $pinsTable->get($ids);
		// Push pins into blocks.
		foreach ($pins as $pin) {
			// Use pin name as object member.
			// Each pin is an array.
			// e.g blocks[ID]->pages[] = PAGE-ID.
		  $blocks[$pin->blockId]->{$pin->pin}[] = $pin->value;
		}
		return $blocks;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function getInfo($id) {
		// Info fields.
		$infoFields = array('name', 'id', 'owner', 'created', 'lastModified');
		// Get info fields from db.
		$blocksTable = new CJTBlocksTable($this->dbDriver);
		$info = $blocksTable->get($id, $infoFields);
		// Get user object from id.
		$info = reset($info);
		$info->owner = get_userdata($info->owner);
		// Set shortcode name.
		$info->shortcode = "[cjtoolbox name='{$info->name}']";
		return $info;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getOrder() {
		return get_option('meta-box-order_cjtoolbox');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		$this->dbDriver->processQueue();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $order
	*/
	public function setOrder($order) {
		$orderOptionName = 'meta-box-order_cjtoolbox';
		// Update user order so That jQuery sortable Plugin will display them in correct orders!
		update_user_option(get_current_user_id(), $orderOptionName, $order, true);
		// Update CENTRALIZED order in the options table!
		update_option($orderOptionName, $order);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $block
	*/
	public function update($block, $updatePins) {
		$block = (array) $block; // To be used by array_intersect_key.
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		$pins = new CJTBlockPinsTable($this->dbDriver);
		// Update block pins if requested.
		if ($updatePins) {
			// Isolate block pins freom native block data.
			$pinsData = array_intersect_key($block, array_flip(array('pages', 'posts', 'categories')));
			$pins->update($block['id'], $pinsData);		
		}
		// Isolate block fields.
		$block = array_intersect_key($block, $blocks->getFields());
		$blocks->update($block);
	}
	
} // End class.