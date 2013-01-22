<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerBackup extends ArrayIterator {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($backups) {
		// Initialize Array Iterator!
		parent::__construct(is_array($backups) ? $backups : array());
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		// Save backup in backup table!
		$backup = $this->current();
		// Change 'time' field to 'created'!
		// Convert formatted date to mysal date!
		$backup['created'] = $backup['time'];
		// User author Id instead of name and change 'author' field to 'owner'!
		$backup['owner'] = get_user_by('login', $backup['author'])->ID;
		$backup['type'] = 'blocks'; // For now we support only blocks backups!
		// Load blocks into blocks iterator before destroying deprectaed fields.
		$blocks = new CJTInstallerBlocks03($backup['data'], CJTInstallerBlocks03::BLOCK_TYPE_BACKUP);
		// Remove deprecated fields!
		$backup = array_diff_key($backup, array_flip(array('time', 'author', 'data')));
		/// Add backup and get its ID using OLD style table (not xTable)!!
		// Import dependecneis.
		cssJSToolbox::import('tables:backups.php');
		// Insert backup record.
		$backupsTable = new CJTBackupsTable(cssJSToolbox::getInstance()->getDBDriver());
		$backupsTable->insert($backup);
		$backupId = $backupsTable->getDBDriver()->processQueue()->getInsertId();
		// Insert all blocks!
		foreach ($blocks as & $block) {
			// Associate every block with the created backup!
			$block['backupId'] = $backupId;
			// Upgrade block!
			$blocks->upgrade();
			$blocks->model->save();
		}
		return $this;
	}
	
} // End class!