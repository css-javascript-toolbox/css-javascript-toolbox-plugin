<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Taxonomy
extends CJT_Models_Block_Assignmentpanel_Taxonomybase {

	/**
	* put your comment there...
	* 
	*/
	public function getTotalCount() {
		// Initialize.
		$args = $this->args;
		$params =& $this->getTypeParams();
		// Set args.
		$args['fields'] = 'ids';
		// Query posts.
		return count(get_terms($params['type'], $args));
	}

	/**
	* put your comment there...
	* 
	*/
	protected function isHierarchical() {
		// Initialize.
		$typeParams =& $this->getTypeParams();
		// Check if post_type hierarchical.
		return is_taxonomy_hierarchical($typeParams['type']);
	}

  /**
  * put your comment there...
  * 
  *
  */
  protected function queryItems() {
		// Initialize.
		$args = $this->args;
		$params =& $this->getTypeParams();
		// Set User passed parameters used to query 'taxonomies'
		if ($this->isHierarchical()) {
			// Return them all for taxonomy doesn't 
			// require the end-offset ($args['number']) to be set.
			$args['offset'] = 0;
		}
		else {
			$args['offset'] = $this->getOffset();
			$args['number'] = $this->getIPerPage();
		}
		// Query posts.
		return get_terms($params['type'], $args);
  }

} // End class.
