<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Block_Assignmentpanel_Postbase
extends CJT_Models_Block_Assignmentpanel_Wordpress {
	
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
	* @param mixed $key
	* @param mixed $item
	*/
	protected function prepareItem($key, & $item) {
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

} // End class.
