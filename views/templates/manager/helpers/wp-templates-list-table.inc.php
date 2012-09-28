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
		$args = array(
		
		);
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
		return $item->{$name};
	}

	/**
	* put your comment there...
	* 
	*/
	public function display() {
		// Set pagination parameters.
		$this->set_pagination_args(array(
			'per_page' => 40,
			'total_items' => count($this->items)
		));
		// Display table.
		parent::display();	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get_bulk_actions() {
		// Bulk ations.
		$actions = array(
			'delete' => cssJSToolbox::getText('Delete'),
			'edit' => cssJSToolbox::getText('Edit'),
			'disable' => cssJSToolbox::getText('Disable'),
			'enable' => cssJSToolbox::getText('Enable')
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
			'name' => 'Name',
			'id' => 'ID',
		);
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
	
}