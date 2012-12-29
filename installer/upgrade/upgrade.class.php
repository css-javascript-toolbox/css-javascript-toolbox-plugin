<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTUpgradeNonTabledVersions extends CJTHookableClass {
	
	/**
	* 
	*/
	const BLOCKS_POINTER = 'cjtoolbox_data';
	
	/**
	* 
	*/
	const TEMPLATES_TABLE = '#__cjtoolbox_cjdata';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $blocks;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $templates;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Import dependencies!
		cssJSToolbox::import('installer:upgrade:block.class.php', 'installer:upgrade:template.class.php');
		// Load blocks into installer iterator!
		$this->blocks = new CJTInstallerBlock(get_option(self::BLOCKS_POINTER));
		// Load templates into templates iterator!
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		$templates = $driver->select('SELECT title as `name`, type, `code` FROM ' . self::TEMPLATES_TABLE . ';', ARRAY_A);
		$this->templates = new CJTInstallerTemplate($templates);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function blocks() {
		// Version 0.2 and 0.3 use wrong algorithm for saving blocks order!
		// Every use has its owen blocks order however the orders is not used to output the blocks!
		// Version 2.0 still has argument about this but for simplification sake and for time save
		// We just get orders from current runnign user! This is not 100% correct but we just need to advice 
		// to install the Plugin using the same author you need to inherits the order from!
		$page = CJTPlugin::PLUGIN_REQUEST_ID;
		// Get current logged-in user order!
		$order = get_user_option("meta-box-order_settings_page_{$page}");
		// Save it into GLOBAL/SHARED option to centralized all users!
		$this->blocks->model->setOrder($order);
		// Delete old blocks data!
		delete_option(self::BLOCKS_POINTER);
		return true;
	}
	
	/**
	* put your comment there...
	* 
	*/	
	public function templates() {
		// Tranform templates to the new table!
		foreach ($this->templates as $template) {
			$this->templates->upgrade();
		}
		// Drop cjdata table as is it no longed needed!
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		$driver->exec('DROP TABLE #__cjtoolbox_cjdata;');
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		// Upgrade Templates!
		$this->templates();
		// Upgrade Blocks!
		$this->blocks();
		// Chaining!
		return $this;
	}
	
} // End class.