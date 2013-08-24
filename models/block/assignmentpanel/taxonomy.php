<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Taxonomy
extends CJT_Models_Block_Assignmentpanel_Base {

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $args = array(
	  'child_of' => 0,
		'exclude' => '',
	  'hide_empty' => false,
		'hierarchical' => 1,
	  'include' => '',
		'include_last_update_time' => false,
	  'order' => 'ASC',
		'orderby' => 'name',
	  'pad_counts' => false,
	);
  
	/**
	* put your comment there...
	* 
	* @param mixed $item
	*/
	protected function prepareItem(& $item) {
		// Initialize.
		$typeParams = $this->getTypeParams();
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

  /**
  * put your comment there...
  * 
  *
  */
  protected function queryItems() {
		// Initialize.
		$args = $this->args;
		$params = $this->getTypeParams();
		// Set User passed parameters used to query 'posts'
		$args['hide_empty'] = false;
		$args['order'] = 'DESC';
		$args['offset'] = $this->getOffset();
		$args['number'] = $this->getIPerPage();
		// Query posts.
		return get_terms($params['type'], $args);
  }

} // End class.
