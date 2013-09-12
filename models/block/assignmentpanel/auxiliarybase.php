<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Block_Assignmentpanel_Auxiliarybase
extends CJT_Models_Block_Assignmentpanel_Base {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $auxHelper = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $offset
	* @param mixed $iPerPage
	* @param mixed $blockId
	* @param mixed $typeParams
	* @return CJT_Models_Block_Assignmentpanel_Auxiliarybase
	*/
	public function __construct($offset, $iPerPage, $blockId, $typeParams) {
		// Initialize.
		$this->auxHelper = new CJT_Models_Block_Assignmentpanel_Helpers_Auxiliary();
		// Initialize parent.
		parent::__construct($offset, $iPerPage, $blockId, $typeParams);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getList() {
		// Initialize.
		$list = array();
		$offset = $this->getOffset();
		// Don't return items unless the offset is 0
		// there is no paging for the aux items.
		if (!$offset) {
			$list = $this->auxHelper->getList();
		}
		// Returns
		return $list;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function isHierarchical() {
		return false;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function pinsMap() {
		// Initialize.
		$map = array();
		$mdlBlocks = new CJTBlocksModel();
		$blockId = $this->getBlockId();
		// Query block pinPoint field.
		$assignedPinPoints = $mdlBlocks->getBlock($blockId, array(), array('id', 'pinPoint'), false)->pinPoint;
		// Return map.
		return $this->auxHelper->getPinsArray($assignedPinPoints);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $key
	* @param mixed $item
	*/
	protected function prepareItem($key, & $item) {
		// Get the title.
		$title = $item;
		// Create an item array.
		$item = array();
		$item['parent'] = 0;
		$item['id'] = dechex($key);
		$item['title'] = $title;
	}
	
} // End class.
