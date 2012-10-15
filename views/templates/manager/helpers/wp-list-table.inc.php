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
		// Import dependencies.
		cssJSToolbox::import('tables:authors.php');
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
			case 'lastVersion':
			case  'revisions':
			case  'releases':
				$value = ($item->{$name} === null) ? cssJSToolbox::getText('Not Versioned') : $item->{$name};
			break;
			case 'name':
				// Display cell value as regular.
				$value  = $item->{$name};
				// Display row actions underneath template name column.
				$actions = array();
				$actions['edit'] = "<a href='#{$item->guid}'>" . cssJSToolbox::getText('Edit') . '</a>';
				$actions['delete'] = "<a href='#{$item->guid}'>" . cssJSToolbox::getText('Delete') . '</a>';
				$actions['checkout'] = "<a href='#{$item->guid}'>". cssJSToolbox::getText('Check Out') . '</a>';
				$actions['revisions'] = "<a href='#{$item->guid}'>". cssJSToolbox::getText('Revisions') . '</a>';
				$value .= $this->row_actions($actions, false);
			break;
			case '_selection_':
				echo "<input type='checkbox' name='guid[]' value='{$item->guid}' />";
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
	function extra_tablenav($which) {
		// Define filters.		
		$filters = array();
		$filters[] = 'template-types';
		$filters[] = 'authors';
		$filters[] = 'owners';
		// Get the HTML field for each filter antput the result.
		foreach ($filters as $name) { 
			// Import field file.
			cssJSToolbox::import("models:fields:{$name}.php");
			// Get field class name.
			$name = str_replace('-', '', ucfirst($name));
			$fieldClass = "CJT{$name}Field";
			// Output field markup.
			$fieldName = "filter_{$name}";
			$classes = "filter filter_{$name}";
			echo call_user_func(array($fieldClass, 'getInstance'), 'templates-manager', $fieldName, $_REQUEST[$fieldName], null, $classes)->getInput();
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get_bulk_actions() {
		// Bulk ations.
		$actions = array(
			'delete' => __('Delete'),
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
			'_selection_' => '<input type="checkbox" class="select-all" />',
			'name' => cssJSToolbox::getText('Name'),
			'description' => cssJSToolbox::getText('Description'),
			'lastVersion' => cssJSToolbox::getText('Last Version'),
			'revisions' => cssJSToolbox::getText('Revisions'),
			'releases' => cssJSToolbox::getText('Releases'),
			'type' => cssJSToolbox::getText('Type'),
			'authorName' => cssJSToolbox::getText('Author'),
			'ownerName' => cssJSToolbox::getText('Owner'),
			'guid' => cssJSToolbox::getText('GUID'),
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get_sortable_columns() {
		$sortables = array();
		$sortables['name'] = 'name';
		$sortables['lastVersion'] = 'LastVersion';
		$sortables['revisions'] = 'revisions';
		$sortables['type'] = 'type';
		$sortables['authorName'] = 'authorName';
		$sortables['ownerName'] = 'ownerName';
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