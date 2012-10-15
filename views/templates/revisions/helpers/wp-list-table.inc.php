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
class CJTTemplateRevisionsListTable extends WP_List_Table {
	
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
	* 
	*/
	protected function column_default($item, $name) {
		$value = null;
		switch ($name) {
			case '_selection_':
				echo "<input type='checkbox' name='guid[]' value='{$item->guid}' />";
			break;
			case 'isTagged':
				$value = ($item->{$name} == 1) ? __('Release') : __('Revision');
			break;
			case 'revisionNo':
				// Display cell value as regular.
				$value  = $item->{$name};
				// Display row actions underneath template name column.
				$actions = array();
				$actions['download'] = "<a href='#{$item->guid}'>". cssJSToolbox::getText('Download') . '</a>';
				$actions['checkout'] = "<a href='#{$item->guid}'>". cssJSToolbox::getText('Check-Out') . '</a>';
				// Allow revisions to be tagged.
				if ($item->isTagged == -1) {
					$actions['tag'] = "<a href='#{$item->guid}'>". cssJSToolbox::getText('Tag') . '</a>';	
				}
				// If author is a Local author allow uploading.
				if ($item->attributes & 2) {
					$actions['upload'] = "<a href='#{$item->guid}'>". cssJSToolbox::getText('CJT-Upload') . '</a>';
				}
				$value .= $this->row_actions($actions, false);
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
		$filters[] = 'states';
		$filters[] = 'authors';
		$filters[] = 'owners';
		$filters[] = 'releases';
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
			echo call_user_func(array($fieldClass, 'getInstance'), 'manage-form', $fieldName, $_REQUEST[$fieldName], null, $classes)->getInput();
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
			'tag' => __('Tag'),
			'publish' => __('Publish'),
			'trash' => __('Trash'),
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
			'revisionNo' => cssJSToolbox::getText('Revision No#'),
			'version' => cssJSToolbox::getText('Version'),			
			'isTagged' => cssJSToolbox::getText('Release'),
			'changeLog' => cssJSToolbox::getText('Change Log'),
			'state' => cssJSToolbox::getText('State'),
			'ownerName' => cssJSToolbox::getText('Owner'),
			'authorName' => cssJSToolbox::getText('Author'),
			'guid' => cssJSToolbox::getText('GUID'),
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get_sortable_columns() {
		$sortables = array();
		$sortables['version'] = 'version';
		$sortables['state'] = 'state';
		$sortables['isTagged'] = 'isTagged';
		$sortables['ownerName'] = 'ownerName';
		$sortables['authorName'] = 'authorName';
		return $sortables;
	}
	
} // End class.