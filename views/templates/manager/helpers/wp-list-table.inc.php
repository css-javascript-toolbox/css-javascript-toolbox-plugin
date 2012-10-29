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
			case 'state':
			case 'type':
				$value = cssJSToolbox::getText($item->{$name});
			break;
			case 'name':
				// Display cell value as regular.
				$value  = $item->{$name};
				// Show description. Truncate description/display breif desc.
				$brief = implode(' ', array_shift(array_chunk(explode(' ', $item->description), 20)));
				$value .= '<br />';
				$value .= "<div class='description'><span>{$brief}</span>";
				if (strlen($brief) < strlen($item->description)) {
					$value .= '.... ';
				}
				$value .= '</div>';
				// Display row actions underneath template name column.
				$actions = array();
				$actions['info'] = "<a href='#{$item->id}'>" . cssJSToolbox::getText('Info') . '</a>';
				$actions['edit'] = "<a href='#{$item->id}'>" . cssJSToolbox::getText('Edit') . '</a>';
				$actions['delete'] = "<a href='#{$item->id}'>" . cssJSToolbox::getText('Delete') . '</a>';
				// Show only states that the Template s not in!
				$states = CJTStatesField::getStates();
				unset($states[$item->state]);
				foreach ($states as $name => $props) {
					$actions[$name] = "<a href='#{$item->id}'>{$props['text']}</a>";
				}
				// Show actions row underneath template name!!
				$value .= $this->row_actions($actions, false);
			break;
			case '_selection_':
				echo "<input type='checkbox' name='id[]' value='{$item->id}' />";
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
		$filters[] = 'versions';
		$filters[] = 'creation-dates';
		$filters[] = 'authors';
		$filters[] = 'states';
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
			'delete' => cssJSToolbox::getText('Delete'),
			'publish' => cssJSToolbox::getText('Publish'),
			'trash' => cssJSToolbox::getText('Trash'),
			'draft' => cssJSToolbox::getText('Draft'),
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
			'type' => cssJSToolbox::getText('Type'),
			'version' => cssJSToolbox::getText('Version'),
			'author' => cssJSToolbox::getText('Author'),
			'developmentState' => cssJSToolbox::getText('Release'),
			'creationDate' => cssJSToolbox::getText('Date Created'),
			'lastModified' => cssJSToolbox::getText('Last Modified'),
			'state' => cssJSToolbox::getText('State'),
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get_sortable_columns() {
		$sortables = array();
		$sortables['name'] = 'name';
		$sortables['type'] = 'type';
		$sortables['creationDate'] = 'creationDate';
		$sortables['lastModified'] = 'lastModified';
		$sortables['state'] = 'state';
		$sortables['version'] = 'version';
		$sortables['developmentState'] = 'developmentState';
		$sortables['author'] = 'author';
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