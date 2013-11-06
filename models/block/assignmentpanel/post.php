<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Post
extends CJT_Models_Block_Assignmentpanel_Postbase {

	/**
	* put your comment there...
	* 
	*/
	public function getTotalCount() {
		// Initialize.
		$args = $this->args;
		$params =& $this->getTypeParams();
		// Set args.
		$args['offset'] = 0;
		$args['numberposts'] = -1;
		$args['post_type'] = $params['type'];
		// Query posts.
		return count(get_posts($args));
	}

	/**
	* put your comment there...
	* 
	*/
	protected function isHierarchical() {
		// Initialize.
		$typeParams =& $this->getTypeParams();
		// Check if post_type hierarchical.
		return is_post_type_hierarchical($typeParams['type']);
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
		// Set User passed parameters used to query 'posts'
		if ($this->isHierarchical()) {
			// Fetch all for Hierarchical posts.
			$args['offset'] = 0;
			$args['numberposts'] = -1;
		}
		else {
			$args['offset'] = $this->getOffset();
			$args['numberposts'] = $this->getIPerPage();
		}
		$args['post_type'] = $params['type'];
		// Query posts.
		return get_posts($args);
  }

} // End class.