<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Post
extends CJT_Models_Block_Assignmentpanel_Base {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $args = array(
		'order' => 'ASC',
		'orderby' => 'title',
		'suppress_filters' => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false
	);
	
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
	* Get only item ID, Title, Link.
	* 
	* @param mixed $item
	*/
	protected function prepareItem(& $item) {
		// Initialize.
		$typeParams =& $this->getTypeParams();
		// Get only 'ID' and 'Title', 'parent' fields.
		$preItem['id'] = $item->ID;
		$preItem['title'] = $item->post_title;
		$preItem['parent'] = $item->post_parent;
		// Get permalink.
		$preItem['link'] = get_permalink($preItem['id']);
		/// Flag if term has child terms!
		// Query childs.
		$args = $this->args;
		$args['post_parent'] = $preItem['id'];
		$args['post_type'] = $typeParams['type'];
		$args['numberposts'] = 1;
		$childPosts = get_posts($args);
		// Flag it!
		$preItem['hasChilds'] = !empty($childPosts);
		// Re-Reference.
		$item = $preItem;
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