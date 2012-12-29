<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies!
cssJSToolbox::import('installer:upgrade:upgrade.class.php');

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
		cssJSToolbox::import('installer:upgrade:0.3:includes:backup.class.php');
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
	public function blocks() {
		// Dependencies!
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		// Transform blocks one by one as there're data need to be mainpulated!
		foreach ($this->blocks as $key => &$block) {
			// Prepare block data!
			$block['name'] = $block['block_name'];
			$block['state'] = 'active'; // Defautt to active!
			$block['location'] = ($block['location'] == 'wp_head') ? 'header' : 'footer'; // Re-map location name!
			// Version 0.3 save all scripts (templates in version 6.0) in a comma separated list!
			$scripts = explode(',', $block['scripts']);
			// Associate block templates!
			if (!empty($scripts)) {
				$blockTemplates = CJTxTable::getInstance('block-template')->set('blockId', $key);
				$template = CJTxTable::getInstance('template')->setTableKey(array('queueName'));
				// For every script/template name find the id of the template and associate it to the block!
				foreach ($scripts as $scriptName) {
					// Get template id from name!
					$templateId = $template->setData(array('queueName' => $scriptName))->load()->get('id');
					if ($templateId) { // Template found!
						// Add template!
						$blockTemplates->set('templateId', $templateId)->save(true);
					}
					// Support saving by template name form block-templates table!
				}				
			}
			// Remove deprecated field!
			$block = array_diff_key($block, array_flip(array('block_name', 'scripts')));
			// Upgrade Block!
			$this->blocks->upgrade();
		}
		// Save all changes!
		$this->blocks->model->save();
		// Do other cleanup and common behavior!
		return parent::blocks();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJTV03Upgrade();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		// Upgrade Non Tabled versions!
		parent::upgrade();
		// Upgrade backups!
		//-----$this->backups();	
		// Chaining!
		return $this;
	}
	
} // End class.