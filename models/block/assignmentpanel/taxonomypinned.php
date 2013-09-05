<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Taxonomypinned
extends CJT_Models_Block_Assignmentpanel_Taxonomybase {

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
	* @param mixed $key
	* @param mixed $item
	*/
	protected function prepareItem($key, & $item) {
		// Prepare item.
		parent::prepareItem($key, $item);
		// No parents used in assignedOnly items.
		$item['parent'] = 0;
	}

  /**
  * put your comment there...
  * 
  *
  */
  protected function queryItems() {
  	// Initialize.
  	$params = $this->getTypeParams();
  	// Get all assigned IDs.
  	$ids = array_keys($this->getPinsMap());
  	// Query all available Ids.
  	$args = $this->args;
  	$args['include'] = $ids;
  	// Return empty list if nothing assigned
  	// or the queried items otherwise.
  	$items = empty($ids) ? array() : get_terms($params['type'], $args);
  	// Return taxonomy items.
  	return $items;
  }

} // End class.
