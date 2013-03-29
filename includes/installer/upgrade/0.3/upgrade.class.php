<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies!
cssJSToolbox::import('includes:installer:upgrade:upgrade.class.php');

/**
* 
*/
class CJTV03Upgrade extends CJTUpgradeNonTabledVersions {
	
	/**
	* 
	*/
	const BACKUPS_POINTER = 'cjtoolbox_tools_backups';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $backups;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Import dependencies!
		cssJSToolbox::import('includes:installer:upgrade:0.3:includes:backup.class.php');
		// Instantiate Backups iterator!		
		$this->backups = new CJTInstallerBackup(get_option(self::BACKUPS_POINTER));
		// Initialize hookable!
		parent::__construct();
	}

	/**
	* put your comment there...
	* 
	*/
	public function backups() {
		// No custom work is neede for now just upgrade backups!
		foreach ($this->backups as $backup) {
			$this->backups->upgrade();
		}
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function finalize() {
		// Delete backups data!
		delete_option(self::BACKUPS_POINTER);
		// Parent finalize!
		return parent::finalize();	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blocks
	*/
	protected function getBlocksIterator($blocks)  {
		// Import iterator class file!
		cssJSToolbox::import('includes:installer:upgrade:0.3:includes:block.class.php');
		// Instantiate blocks iterator object!
		return new CJTInstallerBlocks03($blocks);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJTV03Upgrade();
	}
	
} // End class.