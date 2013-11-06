<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Helpers_Hierarchical {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $allItems = array();
	
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
	* @param mixed $offset
	* @param mixed $iPerPage
	* @param mixed $items
	* @return CJT_Models_Block_Assignmentpanel_Helpers_Hierarchical
	*/
	public function __construct($offset, $iPerPage, $allItems) {
		// Initialize.
		$this->offset = $offset;
		$this->iPerPage = $iPerPage;
		$this->allItems = $allItems;
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
	public function getItems() {
		// Initialize.
		$allItems =& $this->allItems;
		$hierarchicalItems = array();
		$groupedItems = array();
		$offset = $this->getOffset();
		$endOffset = $this->getOffset() + $this->getIPerPage();
		// Group all items under parent item(s) ID.
		foreach ($allItems as $item) {
			$groupedItems[((int) $item['parent'])][$item['id']] = $item;
		}
		/// Non-Recursive loop for getting
		/// FLAT list for all items with childs items
		/// underneath them
		// Working pointers has the current in process
		// items. Initialize to start with root
		// items with parent = 0 (array(0)).
		$workingPointers = array(0);
		do {
			// Current pointer is the last one.
			$pointer = end($workingPointers);
			// Get current working group.
			$group =& $groupedItems[$pointer];
			// Shift first item from group.
			$item = array_shift($group);
			// Add item to the list.
			$hierarchicalItems[$item['id']] = $item;
			// Remove current group from the pointers
			// list if all items has been processed.
			if (empty($group)) {
				array_pop($workingPointers);
			}
			// If the current items has childs then
			// add it to the working pointer to be processed
			// in the immediate iteration.
			if (isset($groupedItems[$item['id']])) {
				$workingPointers[] = $item['id']; 	
			}
			// If there is no other group to process then exit.
		} while(!empty($workingPointers) && (count($hierarchicalItems) != $endOffset));
		// Get ony items start from the requested offset.
		$items = array_slice($hierarchicalItems, $offset, $this->getIPerPage());
		// Returns full-tree.
		return $items;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getOffset() {
		return $this->offset;
	}

} // End class.
