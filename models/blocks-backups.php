<?php
/**
* 
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

// Blocks Database tables.
require_once CJTOOLBOX_TABLES_PATH . '/backups.php';
// MYSQL Queue Driver.
require_once CJTOOLBOX_INCLUDE_PATH . '/db/mysql/queue-driver.inc.php';

/**
* 
*/
class CJTBlocksBackupsModel {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $backups = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $dbDriver = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		$this->dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$this->backups = new CJTBackupsTable($this->dbDriver);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $srcBackupId
	* @param mixed $desBackupId
	*/
	public function copyBackupBlocks($srcBackupId, $desBackupId) {
		// Initialie.
		$codeFilesQuery = 'INSERT INTO #__cjtoolbox_block_files (blockId, id, name, description, `type`, code, `order`)
												 SELECT %d, id, name, description, `type`, code, `order` FROM #__cjtoolbox_block_files WHERE blockId = %d;';
		// Blocks Table & Model..
		require_once CJTOOLBOX_TABLES_PATH . '/blocks.php';
		require_once CJTOOLBOX_TABLES_PATH . '/block-pins.php';
		require_once CJTOOLBOX_MODELS_PATH . '/blocks.php';
		// Get blocks Table & Model instances.
		$blocksTable = new CJTBlocksTable($this->dbDriver);
		$blockPinsTable = new CJTBlockPinsTable($this->dbDriver);
		$blocksModel = new CJTBlocksModel();
		// Get source backup blocks.
		$blocks['fields'] = array('*');
		$blocks['filters']['backupId'] = $srcBackupId;
		$blocks['filters']['types'] = array('block', 'revision');
		// Its important to get revision blocks at the end
		// to create blocks id map.
		$blocks['orderby'] = array('type');
		$blocks = $blocksModel->getBlocks(null, $blocks['filters'], $blocks['fields'], OBJECT_K, $blocks['orderby']);
		// Prepare vars before copying.
		$desBlockId = $blocksTable->getNextId();
		// For every block give new Id and insert block and pins data.
		$blocksMap = array();
		foreach ($blocks as $id => $block) {
			// Change block Id & backupId.
			$block->id = $desBlockId++;
			$block->backupId = $desBackupId;
			// Change revision "parentId" to the new block id.
			if ($block->type == 'block') {
				// Map the old id to the new one.
				$blocksMap[$id] = $block->id;
			}
			else if ($block->type = 'revision') {
				// Exclude revision blocks for other types than 'block' type (e.g metabox revisions).
				if (!isset($blocksMap[$block->parent])) {
					continue;
				}
				/*
				* Use the new id instead of the old one.
				* Get the parent block id from the map.
				*/
				else {
					$block->parent = $blocksMap[$block->parent];
				}
			}
			// Cast stdClass to array.
			$block = (array) $block;
			// Insert block.
			$blockData = array_intersect_key($block, $blocksTable->getFields());
			$blocksTable->insert($blockData);
			// Insert block Pins.
			$pinsData = array_intersect_key($block, array_flip(array('pages', 'posts', 'categories')));
			$blockPinsTable->insert($block['id'], $pinsData);
			// Copy code files.
			$this->dbDriver->insert(sprintf($codeFilesQuery, $block['id'], $id))->processQueue();
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param int|string $backupData
	*/
	public function create($backupData) {
		// Prepare backup data.
		$backupData['owner'] = get_current_user_id();
		$backupData['created'] = current_time('mysql');
		$backupData['type'] = 'blocks';
		$backupData['id'] = $this->backups->getNextId();
		// Don't do anything is any statement fails!
		$this->dbDriver->startTransaction();
		// Insert new backup id.
		$this->backups->insert($backupData);
		// Copy Blocks to newly created backup.
		$this->copyBackupBlocks(null, $backupData['id']);
		// Commit Changes.
		$this->dbDriver->commit();
		// Get Owner User object for the returned object.
		$backupData['owner'] = get_userdata($backupData['owner']);
		// Return backups data back.
		return $backupData;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function delete($id) {
		// Get backup record key.
		$backupKey['id'] = $id;
		// Delete backup record.
		$this->backups->delete($backupKey);
		// Delete backup blocks.
		$this->deleteBackupBlocks($id);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $backupId
	* @param boolean Dont delete master blocks with backupId = NULL. This is very important in case $id param is null for any reason!
	*/
	public function deleteBackupBlocks($backupId = null, $deleteMaster = false) {
		if (!$backupId && !$deleteMaster) {
			throw new Exception('Trying to delete master blocks while deleting normal backup');
		}
		// Blocks Table & Model..
		require_once CJTOOLBOX_TABLES_PATH . '/blocks.php';
		require_once CJTOOLBOX_MODELS_PATH . '/blocks.php';
		// Get blocks Table & Model instances.
		$blocksTable = new CJTBlocksTable($this->dbDriver);
		$blocksModel = new CJTBlocksModel();
		// Get backup blocks Ids.
		$blocks['fields'] = array('id');
		$blocks['filters']['backupId'] = $backupId;
		// Don't delete 'metabox' blocks as its not part of the backup anyway!
		$blocks['filters']['types'] = array('block', 'revision');
		// Query backup blocks.
		$blocks = $blocksTable->get(null, $blocks['fields'], $blocks['filters']);
		// Delete blocks using its Id.
		$ids = array_keys($blocks);
		$blocksModel->delete($ids);
		// Delete code files.
		$this->dbDriver->delete('DELETE FROM #__cjtoolbox_block_files where blockId IN (' . implode(',', $ids) . ');');
		// In order for $this->processQueue to work
		// we need to Merge db driver queues into the current
		// local queue.
		$this->dbDriver->merge($blocksModel->dbDriver());
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getAll() {
		$backups = $this->backups->get('blocks');
		// For every backup get owner data.
		foreach ($backups as &$backup) {
			$backup->owner = get_userdata($backup->owner);
		}
		return $backups;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $backupId
	*/
	public function restore($backupId) {
		// Delete Current blocks.
		$this->deleteBackupBlocks(null, true);
		// Copy Backup Blocks.
		$this->copyBackupBlocks($backupId, null);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		$this->dbDriver->processQueue();
	}
	
} // End class.