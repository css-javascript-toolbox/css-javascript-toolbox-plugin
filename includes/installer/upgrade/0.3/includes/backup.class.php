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
		$backup['created'] = $backup['time'];
		// User author Id instead of name and change 'author' field to 'owner'!
		print_r($backup);
		$backup['owner'] = get_user_by('login', $backup['author'])->ID;
		$backup['type'] = 'block'; // For now we support only blocks backups!
		// Add backup blocks in the blocks table associated with the last inserted backup Id!
		$blocks = new CJTInstaller03Block($backup['data']);
		// Remove deprecated fields!
		$backup = array_diff_key($backup, array_flip(array('time', 'author', 'data')));
		// Add backup and get its ID using OLD style table (not xTable :( ))!!
		cssJSToolbox::import('tables:backups.php');
		$backupsTable = new CJTBackupsTable(cssJSToolbox::getInstance()->getDBDriver());
		$backupsTable->insert($backup);
		
		$backupId = $backupsTable->getDBDriver()->processQueue()->getInsertId();
		echo "{$backupId}<br>";
		die();
	}
	
} // End class!