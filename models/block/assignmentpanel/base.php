<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Block_Assignmentpanel_Base {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $blockId = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $iPerPage = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $offset = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $typeParams = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $offset
	* @param mixed $oPerPage
	* @param mixed $blockId
	* @param mixed $typeParams
	* @return CJT_Models_Block_Assignmentpanel_Base
	*/
	public function __construct($offset, $iPerPage, $blockId, $typeParams) {
		// Initialize.
		$this->offset = $offset;
		$this->iPerPage = $iPerPage;
		$this->blockId = $blockId;
		$this->typeParams = $typeParams;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $assignedOnly
	* @param mixed $type
	* @param mixed $offset
	* @param mixed $iPerPage
	* @param mixed $blockId
	* @param mixed $typeParams
	*/
	public static function getInstance($assignedOnly, $type, $offset, $iPerPage, $blockId, $typeParams) {
		// Get type class name.
		$typeName = strtoupper($type);
		// Inistantiate 'pinned' object if assignedOnly flag is set to true.
		if ($assignedOnly) {
			$typeName .= 'pinned';
		}
		// Build items-list handler class name.
		$typeClass = "CJT_Models_Block_Assignmentpanel_{$typeName}";
		// Instantiate type object.
		$typeObject = new $typeClass($offset, $iPerPage, $blockId, $typeParams);
		// Return instance.
		return $typeObject;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		// Initialize.
		$params = $this->getTypeParams();
		$blockId = $this->getBlockId();
		$pinsMap = array();
		$items = array();
		// Pins map.
		$pinsMap = $this->getPinsMap();
		// Query all items by the model class.
		$items = $this->queryItems();
		// Prepare all retrieved items by 'base' and model classes.
		foreach ($items as $key => & $item) {
			// Prepare the item by the model class.
			$this->prepareItem($key, $item);
			// Set if item is assigned to the block!
			$item['assigned'] = isset($pinsMap[$item['id']]);
		}
		// In case the object type is Hierarchical
		// Pass the items list to the filter.
		if ($this->isHierarchical()) {
			// Hierarchical filter.
			$hierarchicalFilter = new CJT_Models_Block_Assignmentpanel_Helpers_Hierarchical(
				$this->getOffset(), 
				$this->getIPerPage(),
				$items
			);
			// Get items with childs in the correct order!
			$items = $hierarchicalFilter->getItems();
		}
		// Return items list.
		return $items;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getBlockId() {
		return $this->blockId;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getIPerPage() {
		return $this->iPerPage;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getOffset() {
		return $this->offset;
	}

	/**
	* Query/Get-Cached pins map.
	* 
	*/
	protected final function getPinsMap() {
		// Initialize.
		static $map = null;
		if ($map === null) {
			$map = $this->pinsMap();
		}
		// Returns.
		return $map;
	}

	public abstract function getTotalCount();
	
	/**
	* put your comment there...
	* 
	*/
	public function & getTypeParams() {
		return $this->typeParams;
	}

	/**
	* put your comment there...
	*
	* @return Boolean 
	*/
	protected abstract function isHierarchical();

	/**
	* put your comment there...
	* 
	*/
	protected abstract function pinsMap();
	
	/**
	* put your comment there...
	* 
	* @param mixed $item
	* @return void
	*/
	protected abstract function prepareItem($key, & $item);

	/**
	* put your comment there...
	* 
	* @return Array Items list.
	*/
	protected abstract function queryItems();
	
} // End class.
