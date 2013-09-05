<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Block_Assignmentpanel_Taxonomybase
extends CJT_Models_Block_Assignmentpanel_Wordpress {
	

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $args = array(
	  'child_of' => 0,
	  'hide_empty' => false,
		'hierarchical' => 1,
		'include_last_update_time' => false,
	  'order' => 'DESC',
		'orderby' => 'name',
	  'pad_counts' => false,
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
		$taxonomy = $typeParams['type'];
		// COMMON names for 'ID' and 'TITLE'
		$preItem['id'] = $item->term_id;
		$preItem['title'] = $item->name;
		$preItem['parent'] = $item->parent;
		// Get taxonomy link.
		$preItem['link'] = get_term_link($item, $taxonomy);
		/// Flag if term has child terms!
		// Query childs.
		$args = $this->args;
		$args['parent'] = $preItem['id'];
		$args['hide_empty'] = false;
		$args['fields'] = 'ids';
		$args['number'] = 1;
		$childTerms = get_terms($typeParams['type'], $args);
		// Flag it!
		$preItem['hasChilds'] = !empty($childTerms);
		// Re-Reference.
		$item = $preItem;
	}

} // End class.
