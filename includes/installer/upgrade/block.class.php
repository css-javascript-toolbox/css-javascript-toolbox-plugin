<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTInstallerBlock extends ArrayIterator {
	
	/**
	* put your comment there...
	* 
	* @var CJTBlocksModel
	*/
	public $model;
	
	/**
	* put your comment there...
	* 
	* @param mixed $blocks
	* @return CJTInstallerBlock
	*/
	public function __construct($blocks) {
		// Initialize!
		$this->model = CJTModel::getInstance('blocks');
		// Initialize Array Iterator class!
		parent::__construct(is_array($blocks) ? $blocks : array());
	}
	
	/**
	* put your comment there...
	* 
	*/
	public  function id() {
		return parent::key() + 1;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function upgrade() {
		// Read block!
		$srcBlock =& $this[$this->key()];
		// Build block in new structure!
		$block = array_diff_key($srcBlock, array_flip(array('page', 'category')));
		$pins = array();
		// Set interna data!
		$block['id'] = $this->id();
		$block['created'] = $block['lastModified'] = current_time('mysql');
		$block['owner'] = get_current_user_id();
		// Translate old assignment panel to use the new structure!
		if (isset($srcBlock['category'])) {
			$pins['categories'] = $srcBlock['category'];
		}
		// Translate named map from last versions to the value used in the new versions!
		CJTModel::import('block'); // Import CJTBlockModel
		$namedPins = array(
			'allpages' => CJTBlockModel::PINS_PAGES_ALL_PAGES,
			'allposts' => CJTBlockModel::PINS_POSTS_ALL_POSTS,
			'frontpage' => CJTBlockModel::PINS_PAGES_FRONT_PAGE,
		);
		foreach ((isset($srcBlock['page']) ? $srcBlock['page'] : array()) as $assignedObject) {
			// Translate named pin to flag!
			if (isset($namedPins[$assignedObject])) {
				// Set pinPoint flags!
				$block['pinPoint'][] = dechex($namedPins[$assignedObject]);
			}
			else { // Previous versions support only pages but not posts!
				$pins['pages'][] = $assignedObject;
			}
		}
		// Calculate Pin Points!
		$block['pinPoint'] = CJTBlockModel::calculatePinPoint($block, $pins);
		// Create new Block!
		$this->model->add($block);
		// Save Block pins/assigned objects as it doesnt saved when created!
		$pins['id'] = $block['id'];
		$this->model->update($pins, true);
		// Chaining
		return $this;
	}
	
} // End class.
