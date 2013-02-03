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
		cssJSToolbox::import('includes:installer:upgrade:block.class.php', 'includes:installer:upgrade:template.class.php');
		// Load blocks into installer iterator!
		$this->blocks = $this->getBlocksIterator(get_option(self::BLOCKS_POINTER));
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
		$defaultOrder = array();
		// Upgrade all blocks.
		foreach ($this->blocks as $index => $block) {
			// In case the user is never re-ordered the blocks then
			// the blocks order is not saved in the meta table
			// however use the blocks order instead!
			$defaultOrder[] = 'cjtoolbox-' . ($index + 1);
			// No customization neede, just upgrade!
			$this->blocks->upgrade();
		}
		// Process DB Driver queue!
		$this->blocks->model->save();
		// Version 0.2 and 0.3 use wrong algorithm for saving blocks order!
		// Every version  has its owen blocks order however the orders is not used to output the blocks!
		// Version 1.0 still has argument about this but for simplification sake and for time save
		// We just get orders from current runnign user! This is not 100% correct but we just need to advice 
		// to install the Plugin using the same author you need to inherits the order from!
		$blocksPageSlug = CJTPlugin::PLUGIN_REQUEST_ID;
		// Get current logged-in user order!
		$order = get_user_option("meta-box-order_settings_page_{$blocksPageSlug}");
		// Save it into GLOBAL/SHARED option to centralized all users!
		if (!$order && !empty($defaultOrder)) {
			$order = array('normal' => implode(',', $defaultOrder));
		}		
		$this->blocks->model->setOrder($order);
		// Sync closed block metaboxes!
		$closedBlocks = get_user_meta(get_current_user_id(), "closedpostboxes_settings_page_{$blocksPageSlug}", true);
		update_user_meta(get_current_user_id(), "closedpostboxes_{$blocksPageSlug}", $closedBlocks);
		return true;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function finalize() {
		// Delete old blocks data!
		delete_option(self::BLOCKS_POINTER);
		// Drop cjdata table as is it no longed needed!
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		$driver->exec('DROP TABLE #__cjtoolbox_cjdata;');
		// Clean up metabox meta data!
		$orderUserMetaKey = 'meta-box-order_settings_page_' . CJTPlugin::PLUGIN_REQUEST_ID;
		$closedUserMetaKey = 'closedpostboxes_settings_page_' . CJTPlugin::PLUGIN_REQUEST_ID;
		$driver->exec("DELETE FROM #__wordpress_usermeta WHERE meta_key IN ('{$orderUserMetaKey}', '{$closedUserMetaKey}');");
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blocks
	*/
	protected abstract function getBlocksIterator($blocks);
	
	/**
	* put your comment there...
	* 
	*/	
	public function templates() {
		// Tranform templates to the new table!
		foreach ($this->templates as $template) {
			$this->templates->upgrade();
		}
		// Chaining!
		return $this;
	}
	
} // End class.