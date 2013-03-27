<?php
/** Following code copied from WordPress core */


/**
* Create HTML list of nav menu input items.
*
* @package WordPress
* @since 3.0.0
* @uses Walker_Nav_Menu
*/
class cj_Walker_Nav_Menu_Checklist extends Walker_Nav_Menu  {
	
	/**
	* put your comment there...
	* 
	* @param mixed $fields
	* @param mixed $boxid
	* @param mixed $type
	* @param mixed $selected
	* @return cj_Walker_Nav_Menu_Checklist
	*/
	function __construct( $fields = false, $boxid = 0, $type = 'page', $selected = array()) {
		if ( $fields ) {
			$this->db_fields = $fields;
		}
		$this->boxid = $boxid;
		$this->selected = $selected;
		$this->type = $type;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $output
	* @param mixed $depth
	*/
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class='children'>\n";
	}

	/**
	* put your comment there...
	* 
	* @param mixed $output
	* @param mixed $depth
	*/
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent</ul>";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$possible_object_id =  $item->object_id;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$output .= $indent . '<li>';
		// Get display text!
		$label = empty( $item->label ) ? esc_html( $item->title ) : esc_html( $item->label );
		// Get permalink
		if($this->type == 'categories') {
			$permalink = get_category_link($item->object_id);
		} else {
			$permalink = get_permalink($item->object_id);
		}
		$output .= '<label >';
		$output .= '<input type="checkbox" ';
		if ( ! empty( $item->_add_to_top ) ) {
			$output .= ' add-to-top';
		}
		$output .= ' name="cjtoolbox['.$this->boxid.']['.$this->type.'][]" value="'. esc_attr( $item->object_id ) .'" ';
		if(is_array($this->selected)) {
			$output .= in_array($item->object_id, $this->selected) ? 'checked="checked"' : '';
		}
		$output .= ' />';
		if ($this->type == 'categories') {
			$args = array(
				'parent' => $item->term_id,
				'hide_empty' => false,
				'fields' => 'ids'
			);
				//print_r($item);
			$childs = get_categories($args);
		}
		else {
			$args = array(
				'post_parent' => $item->ID,
				'post_type' => $item->post_type
			);
			$childs = get_posts($args);
		}
		$output .= '</label>';
		$output .= !empty($childs) ? '<a href="#" class="select-childs-checkbox-overlay"></a><input type="checkbox" class="select-childs" /> ' : ' ';
		$output .= "<span title='{$label}'><a class='extr-link' href='{$permalink}' target='_blank'>{$label}</a></span>";
	}

} // End class.