<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Postpinned
extends CJT_Models_Block_Assignmentpanel_Postbase {

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
  	// All assigned items is fetched in the first request
  	// Multiple requestes is now allowed.
  	if ($this->getOffset()) {
			return array();
  	}
  	// Initialize.
  	$params = $this->getTypeParams();
  	// Get all assigned IDs.
  	$ids = array_keys($this->getPinsMap());
  	// Query all available Ids.
  	$args = $this->args;
  	$args['post_type'] = $params['type'];
  	$args['post__in'] = $ids;
		$args['offset'] = $this->getOffset();
		$args['numberposts'] = -1;
  	// Return empty list if nothing assigned
  	// or the queried items otherwise.
  	$items = empty($ids) ? array() : get_posts($args);
  	// Returns.
  	return $items;
  }

} // End class.