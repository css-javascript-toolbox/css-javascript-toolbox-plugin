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
	function __construct( $fields = false, $boxid = 0, $type = 'page', $selected = array(), $regionMetrics = array() ) {
		if ( $fields ) {
			$this->db_fields = $fields;
		}
		$this->boxid = $boxid;
		$this->selected = $selected;
		$this->type = $type;
		$this->regionMetrics = $regionMetrics;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $output
	* @param mixed $depth
	*/
	function start_lvl( &$output, $depth ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class='children'>\n";
	}

	/**
	* put your comment there...
	* 
	* @param mixed $output
	* @param mixed $depth
	*/
	function end_lvl( &$output, $depth ) {
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
	function start_el(&$output, $item, $depth, $args) {
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
		$output .= " /> ";
		$output .= '</label> <a class="l_ext" target="_blank" href="'. $permalink .'"></a>';
		$output .= "<span title='{$label}'>{$label}</span>";
	}
	
	/**
	* put your comment there...
	* 
	*/
	function wrapText($text, $excludes = 0) {
		$fontSize = $this->regionMetrics['fontSize'];
		$textHolderWidth = $this->regionMetrics['holderWidth'];
		$charactersCount = round($textHolderWidth / $fontSize);
		if ($charactersCount < strlen($text)) {
			$text = substr($text, 0, ($charactersCount - $excludes)) . '...';
		}
		return $text;
	}
	
} // End class.