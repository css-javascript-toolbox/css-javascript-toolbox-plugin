<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Auxiliarypinned
extends CJT_Models_Block_Assignmentpanel_Auxiliarybase {
	
	/**
	* put your comment there...
	* 
	*/
	public function getTotalCount() {
		return count($this->getPinsMap());
	}

	/**
	* put your comment there...
	* 
	*/
	protected function queryItems() {
		// Get list.
		$allItems = $this->getList();
		$items = array();
		// Get only assigned items.
		$map = $this->getPinsMap();
		// Get all aux-items available on the map.
		foreach ($allItems as $id => $item) {
			if (isset($map[dechex($id)])) {
				$items[$id] = $item;
			}
		}
		// Return assigned-only items.
		return $items;
	}

} // End class.
