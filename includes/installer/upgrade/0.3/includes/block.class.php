<?php
/**
* 
*/


// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
cssJSToolbox::import('framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTInstallerBlocks03 extends CJTInstallerBlock {
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		$key = $this->key();
		$id = $this->id();
		$block =& $this[$key];
		// Prepare block data!
		$block['name'] = $block['block_name'];
		$block['state'] = 'active'; // Defautt to active!
		$block['location'] = ($block['location'] == 'wp_head') ? 'header' : 'footer'; // Re-map location name!
		// Cache other fields before clening up deprecated block data!
		$scripts = explode(',', $block['scripts']);
		// Remove deprecated field!
		$this[$key] = array_diff_key($block, array_flip(array('block_name', 'scripts')));
		// Upgrade block (save into db, etc...)
		parent::upgrade();
		// Associate block templates!
		if (!empty($scripts)) {
			$blockTemplates = CJTxTable::getInstance('block-template')->set('blockId', $id);
			$template = CJTxTable::getInstance('template')->setTableKey(array('queueName'));
			// For every script/template name find the id of the template and associate it to the block!
			foreach ($scripts as $scriptName) {
				// Get template id from name!
				$templateId = $template->setData(array('queueName' => $scriptName))->load()->get('id');
				if ($templateId) { // Template found!
					// Add template!
					$blockTemplates->set('templateId', $templateId)->save(true);
				}
			}				
		}
		// Return parent::blocks() returned value!
		return $this;
	}
	
} // End class.