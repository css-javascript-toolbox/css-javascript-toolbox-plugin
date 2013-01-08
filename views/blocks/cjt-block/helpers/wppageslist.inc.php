<?php
/**
* 
*/


/**
* 
*/
abstract class WPPagesListHelper {
	
	/**
	* Get taxanomy terms checkboxes selection list.
	* 
	* @param string List Id.
	* @param array Selected terms list.
	*/
	public static function show_taxonomy_with_checkbox($blockId, $selectedTaxonomies) {
		$taxonomy_name = 'category';
		$args = array(
    		'child_of' => 0,
		    'exclude' => '',
    		'hide_empty' => false,
		    'hierarchical' => 1,
    		'include' => '',
		    'include_last_update_time' => false,
    		'number' => 9999,
    		'order' => 'ASC',
		    'orderby' => 'name',
    		'pad_counts' => false,
	  );
		$terms = get_terms($taxonomy_name, $args);
	  if (!$terms || is_wp_error($terms)) {
		// No items
		    return;
	  }
		$db_fields = false;
		if (is_taxonomy_hierarchical($taxonomy_name)) {
    		$db_fields = array( 'parent' => 'parent', 'id' => 'term_id' );
		}
		$walker = new cj_Walker_Nav_Menu_Checklist($db_fields, $blockId, 'categories', $selectedTaxonomies, $regionMetrics);
		$args['walker'] = $walker;
		echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $terms), 0, (object) $args);
	}
	
	/**
	* Get pages terms checkboxes selection list.
	* 
	* @param string List Id.
	* @param array Selected pages list.
	*/
	public static function show_pages_with_checkbox($blockId, $selectedPages, $postType, $name) {
		$args = array(
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => 9999,
			'post_type' => $postType,
			'suppress_filters' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false
		);
		// @todo transient caching of these results with proper invalidation on updating of a post of this type
		$get_posts = new WP_Query;
		$posts = $get_posts->query($args);
		if (!$get_posts->post_count) {
			// No items
			return;
		}
		$db_fields = false;
		if (is_post_type_hierarchical($postType)) {
			$db_fields = array( 'parent' => 'post_parent', 'id' => 'ID' );
		}
		$walker = new cj_Walker_Nav_Menu_Checklist($db_fields, $blockId, $name, $selectedPages);
		$post_type_object = get_post_type_object($post_type_name);
		$args['walker'] = $walker;
		$checkbox_items = walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $posts), 0, (object) $args);
		echo $checkbox_items;
	}
	
} // End class.