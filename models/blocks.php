<?php
/**
* @version $ Id; blocks.php 21-03-2012 03:22:10 Ahmed Said $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

// Blocks Database tables.
require_once CJTOOLBOX_TABLES_PATH . '/blocks.php';
require_once CJTOOLBOX_TABLES_PATH . '/block-pins.php';
// MYSQL Queue Driver.
require_once CJTOOLBOX_INCLUDE_PATH . '/db/mysql/queue-driver.inc.php';
		
/**
* Provide simple access (read or write) to all Blocks data.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlocksModel {
	
	/**
	* 
	*/
	const MAX_REVISIONS_PER_BLOCK = 10;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize CTTTable MYSQL Driver.
		$this->dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
	}
		
	/**
	* put your comment there...
	* 
	* @param mixed $block
	* @param mixed $forceAddCodeBlock
	*/
	public function add($block, $forceAddCodeBlock = false) {
		// Make sure it array and not stdClass as it has been used from various areas!
		$block = (array) $block;
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		$codeFile = new CJTBlockFilesTable($this->dbDriver);
		// Get new id if not specified.
		if (!isset($block['id']) || !$block['id']) {
			$block['id'] = $blocks->getNextId();
		}
		// Backward compatibility for old block style
		// that has code field inside blocks table.
		/// if passed create master code file for the code field.
		if ($forceAddCodeBlock || isset($block['code'])) {
			// Cache code for MASTER file.
			$codeFile->set('code', $block['code']);
			unset($block['code']); // If it passed it mist be removed from blocks data(avoid field not found)
			// Add code files.
			$codeFile->set('blockId', $block['id'])
							 ->set('id', 1)
							 ->set('name', 'Master')
							 ->save(true, true);
		}
		// Add new block.
		$blocks->insert($block);
		// Return Newly added block id.
		return $block['id'];
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @param mixed $activeFileId
	*/
	public function addRevision($blockId, $activeFileId) {
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		$pins = new CJTBlockPinsTable($this->dbDriver);
		$codeFile = new CJTBlockFilesTable($this->dbDriver);
		// We allow only up to self::MAX_REVISIONS_PER_BLOCK revisions per
		// block code files So that a single block may has up to 
		// self::MAX_REVISIONS_PER_BLOCK * count(codeFiles)
		$revisions['fields'] 	= array('id');
		$revisions['filters'] = array('type' => 'revision', 'parent' => $blockId, 'masterFile' => $activeFileId);
		$revisions = $blocks->get(null, $revisions['fields'], $revisions['filters']);
		// If revisions reached self::MAX_REVISIONS_PER_BLOCK delete first one.
		if (count($revisions) == self::MAX_REVISIONS_PER_BLOCK) {
			$this->delete(array_shift($revisions)->id);
		}
		// Get block data.                                                    
		$block['fields'] = array('id', 'lastModified', 'pinPoint', 'links', 'expressions');
		// get() developed to return multiple blocks, fetch the first.
		$result = $blocks->get($blockId, $block['fields']);
		$block = reset($result);
		// Set other fields.
		$block->location = $block->state = '';
		$block->parent = $blockId;
		$block->type = 'revision';
		$block->created = current_time('mysql');
		$block->owner = get_current_user_id();
		$block->masterFile = $activeFileId; // Only the revisioned code file would be exists and must be 
																				// used as the masterFile!
		$block->id = $blocks->getNextId(); // Get new id for revision rrecord.
		// Add block data.
		$blocks->insert($block);
		// Get block pins and insert pins for the revision block.
		$blockPins = $pins->get($blockId);
		if (!empty($blockPins)) {
			$pins->insertRaw($block->id, $blockPins);
		}
		// Revision current ActiveFileId code record.
		// Simply, get a copy of it from the target block
		// and assign the copy to the new created block revision.
		$codeFile->set('id', $activeFileId)
						 ->set('blockId', $blockId)
						 ->load()
						 ->set('blockId', $block->id)
						 ->save(true, true);
	}
	
	/**
	* Put your comments here...
	*
	*
	* @return 
	*/	
	public function dbDriver() {
  	return $this->dbDriver;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	*/
	public function delete($ids) {
		// Allow single or multiple Ids to be passed.
		if (!is_array($ids)) {
			$ids = array($ids);
		}
		// Create Tables objects.
		$blocks = new CJTBlocksTable($this->dbDriver);
		$pins = new CJTBlockPinsTable($this->dbDriver);
		$codeFile = new CJTBlockFilesTable($this->dbDriver);
		// Get blocks revisions.
		$revisions['fields'] = array('id');
		$revisions['filters']['parent'] = $ids;
		$revisions['type'] = 'revision';
		$revisions['result'] = $blocks->get(null, $revisions['fields'], $revisions['filters']);
		// Revisions ids used as revision key.
		$revisions = array_keys($revisions['result']);
		// Delete all revisions for all "DELETED" blocks
		// only if there is at least one revision.
		if (!empty($revisions)) {
			$blocks->delete($revisions);
			$pins->delete($revisions);
			// Delete code files.
			$this->dbDriver->delete(sprintf('DELETE FROM #__cjtoolbox_block_files WHERE blockId IN(%s)', implode(',', $revisions)));
		}
		// Delete linked templates.
		$linkedOnlyTemplatesQuery = 'DELETE FROM #__cjtoolbox_block_templates WHERE blockId IN(%s)';
		$this->dbDriver->delete(sprintf($linkedOnlyTemplatesQuery, implode(',', $ids)));
		// Delete associated parameters.
		$mdlParams = new CJT_Models_Parameters();
		$mdlParams->delete($ids);
		// Delete form.
		$mdlForm = new CJT_Models_Forms();
		$mdlForm->delete($ids);
		// Delete blocks.
		$blocks->delete($ids);
		$pins->delete($ids);
		// Delete code files.
		$this->dbDriver->delete(sprintf('DELETE FROM #__cjtoolbox_block_files WHERE blockId IN(%s)', implode(',', $ids)));
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	* @param mixed $fields
	*/
	public function getBlock($id, $filters = array(), $fields = array('*'), $useDefaultBackupFltr = true) {
		$blocks = $this->getBlocks($id, $filters, $fields, OBJECT_K, array(), $useDefaultBackupFltr);
		$block = !empty($blocks) ? reset($blocks) : null;
		return $block;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $ids
	*/
	public function getBlocks($ids = array(), $filters = array(), $fields = array('*'), $returnType = OBJECT_K, $orderBy = array(), $useDefaultBackupFltr = true) {
		// initialize.
		$blocks = array();
		$returnCodeFile = isset($filters['returnCodeFile']) && ($filters['returnCodeFile'] == true);
		unset($filters['returnCodeFile']);
		// Create Tables objects.
		$blocksTable = new CJTBlocksTable($this->dbDriver);
		$pinsTable = new CJTBlockPinsTable($this->dbDriver);
		$blockFiles = new CJTBlockFilesTable($this->dbDriver);
		// Read blocks.
		$blocks = $blocksTable->get($ids, $fields, $filters, $returnType, $orderBy, $useDefaultBackupFltr);
		// Get only pins for retrieved blocks.
		$ids = array_keys($blocks);
		$pins = empty($ids) ? array() : $pinsTable->get($ids);
		// Push pins into blocks.
		foreach ($pins as $pin) {
			// Use pin name as object member.
			// Each pin is an array.
			// e.g blocks[ID]->pages[] = PAGE-ID.
		  $blocks[$pin->blockId]->{$pin->pin}[] = $pin->value;
		}
		// Get active file code.
		// There always should be active file unless that the
		// requested block is never switched by current author before.
		// However we'll do that only if filters['returnCodeFile'] set to TRUE.
		if ($returnCodeFile) {
			foreach ($ids as $id) {
				if(!$activeFileId = $this->getCurrentAuthorBlockActiveFileId($id)) {
					// If first time use masterFile ID as he default.
					$activeFileId = $blocks[$id]->masterFile;
				}
				// Retreive code for block code file.
				$codeFile = (array) $blockFiles->setData(array('blockId' => $id, 'id' => $activeFileId))
								 						 					 ->load()
								 						 					 ->getData();
				unset($codeFile['description']);
				$blocks[$id]->file = (object) $codeFile;
				// Also return the active file id withing the set.
				$blocks[$id]->activeFileId = $activeFileId;
			}
		}
		return $blocks;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @param mixed $authorId
	*/
	public function getAuthorBlockActiveFileId($blockId, $authorId = null) {
		// Get author active file for the requested block.
		$activeFileId = (int) get_user_meta($authorId, "cjt_block_active_file_{$blockId}", true);
		// Query block active file.
		return $activeFileId;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @param mixed $authorId
	*/
	public function getCurrentAuthorBlockActiveFileId($blockId) {
		return $this->getAuthorBlockActiveFileId($blockId, get_current_user_id());
	}

	/**
	* put your comment there...
	* 
	*/
	public static function getCustomPostTypes()
	{
		
		static $postTypes = null;
		
		if ( $postTypes !== null )
		{
			return $postTypes;
		}
		
		
		$postTypes = array();
		
		// Create tabs for every custom post under the custom posts tab.
		// Get all registered custom posts.
		$customPosts = get_post_types( array( 'public' => 1, 'show_ui' => true, '_builtin' => false ), 'objects' );

		// Add tab for every custom post
		// Exclude 'Empty' Custom Post Types.
		foreach ( $customPosts as $typeName => $customPost ) 
		{
			// Check if has posts.	
			$hasPosts = count( get_posts( array( 'post_type' => $typeName, 'offset' => 0, 'numberposts' => 1 ) ) );
			
			// Add only types with at least one post exists.
			if ( $hasPosts ) 
			{
				$postTypes[ $typeName ] = array
				(
					'title' => $customPost->labels->name,
					'renderer' => 'objects-list',
					'type' => array
					( 
						'type' => $typeName,
						'group' => 'posts',
						'targetType' => 'post'
					)
					
				);
				
			}
			
		}

		do_action( CJTPluggableHelper::ACTION_BLOCK_CUSTOM_POST_TYPES, $postTypes );
		
		return $postTypes;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function getInfo($id) {
		// Info fields.
		$infoFields = array('name', 'id', 'owner', 'created', 'lastModified');
		// Get info fields from db.
		$blocksTable = new CJTBlocksTable($this->dbDriver);
		$info = $blocksTable->get($id, $infoFields);
		// Get user object from id.
		$info = reset($info);
		$info->owner = get_userdata($info->owner);
		// Set shortcode name.
		$info->shortcode = "[cjtoolbox name='{$info->name}']";
		return $info;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getOrder() {
		return get_option('meta-box-order_cjtoolbox');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		$this->dbDriver->processQueue();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $order
	*/
	public function setOrder($order) {
		$orderOptionName = 'meta-box-order_cjtoolbox';
		// Update user order so That jQuery sortable Plugin will display them in correct orders!
		update_user_option(get_current_user_id(), $orderOptionName, $order, true);
		// Update CENTRALIZED order in the options table!
		update_option($orderOptionName, $order);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $block
	*/
	public function update( $block, $updatePins ) 
	{
		
		$block = ( array ) $block;
		
		$blocks = new CJTBlocksTable( $this->dbDriver );
		$pins = new CJTBlockPinsTable( $this->dbDriver );
		
		// Update block pins if requested.
		if ( $updatePins ) 
		{
			// Isolate block pins freom native block data.
			$pinsData = array_intersect_key( $block, array_flip( array_keys( CJTBlockModel::getCustomPins() ) ) );
			
			do_action( CJTPluggableHelper::FILTER_BLOCK_MODEL_PRE_UPDATE_BLOCK_PINS, $block, $pinsData );
			
			$pins->update( $block[ 'id' ], $pinsData );

		}
		
		// Update code file
		if ( isset( $block[ 'activeFileId' ] ) ) 
		{
			
			$codeFile = new CJTBlockFilesTable( $this->dbDriver );
			
			$codeFile->set( 'blockId', $block[ 'id' ] )
							 ->set( 'id', $block[ 'activeFileId'] )
							 ->set( 'code', $block[ 'code'] )
							 ->save();
		}
		
		// Isolate block fields.
		$blockData = array_intersect_key($block, $blocks->getFields());
		
		do_action( CJTPluggableHelper::FILTER_BLOCK_MODEL_PRE_UPDATE_BLOCK, $updatePins, $blockData );
		                                                                                        
		$blocks->update( $blockData );
		
	}
	
} // End class.