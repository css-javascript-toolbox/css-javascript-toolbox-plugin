<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import WP_List_Table class.
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
* 
*/
class CJTTemplatesManagerListTable extends WP_List_Table {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Table list arguments.
		$args = array();
		parent::__construct($args);
	}
	
	/**
	 * Checks the current user's permissions
	 * @uses wp_die()
	 *
	 * @since 3.1.0
	 * @access public
	 * @abstract
	 */
	public function ajax_user_can() {
		
	}
	
	/**
	* 
	*/
	protected function column_default($item, $name) {
		$value = null;
		switch ($name) {
			case 'author':
				switch ($item->author)	 { // Get hard-coded authors name.
					case '0000000000000000': // Local Shared.
						$value = __('Local Shared');
					break;
					case '0000000000000001': // Wordpress.
					   $value = __('Wordpress');
					break;
					default;
						$value = $item->{$name};
					break;
				}
			break;
			default;
				$value = $item->{$name};
			break;
		}
		return $value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $which
	*/
	function extra_tablenav( $which ) {
		cssJSToolbox::import('models:fields:states.php');
		echo CJTStatesField::getInstance('filter_state', $_GET['filter_state'])->getInput();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get_bulk_actions() {
		// Bulk ations.
		$actions = array(
			'delete' => __('Delete'),
			'edit' => __('Edit'),
			'publishing' => __('Publish'),
			'unpublish' => __('Unpublish')
		);
		// Return actions!
		return $actions;
	}
	
	/**
	 * Get a list of columns. The format is:
	 * 'internal-name' => 'Title'
	 *
	 * @since 3.1.0
	 * @access protected
	 * @abstract
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'name' => __('Name'),
			'author' => __('Author'),
			'description' => __('Description'),
			'state' => __('State'),
			'version' => __('Version'),
			'url' => __('URL'),
			'type' => __('Type'),
			'guid' => cssJSToolbox::getText('GUID'),
		);
	}
	
	public function get_sortable_columns() {
		$sortables = array();
		$sortables['name'] = 'orderby';
		$sortables['author'] = 'orderby';
		$sortables['state'] = 'orderby';
		$sortables['version'] = 'orderby';
		$sortables['type'] = 'orderby';
		return $sortables;
	}
	/**
	 * Prepares the list of items for displaying.
	 * @uses WP_List_Table::set_pagination_args()
	 *
	 * @since 3.1.0
	 * @access public
	 * @abstract
	 */
	public function prepare_items() {
		
	}
	
} // End class.