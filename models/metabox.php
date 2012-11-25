<?php
/**
* 
*/

// No direct access allowed.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
cssJSToolbox::import('tables:blocks.php');

/**
* 
*/
class CJTMetaboxModel {
	
	/** */
	const STATE_CREATED = 'created';

	/** */
	const STATE_DELETED = 'deleted';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $dbDriver = null;
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $post = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $post
	* @return CJTPostModel
	*/
	public function __construct($params) {
		// Get post object from post Id.
		$this->post = get_post($params[0]);
		// Import dependencies.
		cssJSToolbox::import('framework:db:mysql:queue-driver.inc.php', 'framework:db:mysql:table.inc.php');
		// Instantiate MYSQL DB Driver object.
		$this->dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	*/
	public function create(& $pin = null) {
		// Import dependecnes.
		cssJSToolbox::import('tables:block-pins.php');
		// Change metabox status to be created.
		$this->setState(self::STATE_CREATED);
		// Add post pin to pins table.
		$blockPinsTable = new CJTBlockPinsTable($this->dbDriver);
		// Pin data.
		$pin = (object) array();
		// Import CJTBlockModel class.
		CJTModel::import('block');
		/**	@todo Only temporary in version 6.0. Later versions should group all post types by post type name! */
		switch ($this->getPost()->post_type) {
			case 'page':
				$pin->pin = 'pages';
				$pin->flag = CJTBlockModel::PINS_PAGES_CUSTOM_PAGE;
			break;
			default:
				$pin->pin = 'posts';
				$pin->flag = CJTBlockModel::PINS_POSTS_CUSTOM_POST;
			break;
		}
		$pin->value = $this->getPost()->ID;
		// Add pin record.
		$blockPinsTable->insertRaw($this->getMetaboxId(), array($pin));
		// Chains!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function delete() {
		return $this->setState(self::STATE_DELETED);
	}
	
	/**
	* Check whether is the current post type
	* can has CJT Block metabox?
	* 
	*/
	public function doPost() {
		// Get all post types selected by user.
		$metaboxSettings = CJTModel::create('settings')->loadPage('metabox');
		// Return true is post types selected, otherwise return false.
		$allowedPostTypes = $metaboxSettings->postTypes;
		return in_array($this->getPost()->post_type, $allowedPostTypes);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getMetaboxId() {
		$metaboxId = get_post_meta($this->getPost()->ID, CJTBlocksTable::BLOCK_META_BOX_ID_META_NAME, true);
		// Cast Metabox to integer.
		$metaboxId = (int) $metaboxId;
		return $metaboxId;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPost() {
		return $this->post;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function hasBlock() {
		// If $postMeta seto to a blockId then it has block otherwise(0) it hasn't.
		$metaboxStatus = get_post_meta($this->getPost()->ID, CJTBlocksTable::BLOCK_META_BOX_STATUS_META_NAME, true);
		return ($metaboxStatus == self::STATE_CREATED);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function reservedMetaboxBlockId() {
		// Reserved if only if not already taken!
		if (!$reservedId = $this->getMetaboxId()) {
			// Get Blocks table instance.
			$BlocksTable = new CJTBlocksTable($this->dbDriver);
			// Get next Id.
			$reservedId = $BlocksTable->getNextId();
			// Set metabox reserved id.
			update_post_meta($this->getPost()->ID, CJTBlocksTable::BLOCK_META_BOX_ID_META_NAME, $reservedId);
		}
		return $reservedId;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		$this->dbDriver->processQueue();
		// Chains
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $state
	*/
	public function setState($state) {
		// Update state post meta var.
		update_post_meta($this->getPost()->ID, CJTBlocksTable::BLOCK_META_BOX_STATUS_META_NAME, $state);
		// Chaining!
		return $this;
	}
	
}  // End class.