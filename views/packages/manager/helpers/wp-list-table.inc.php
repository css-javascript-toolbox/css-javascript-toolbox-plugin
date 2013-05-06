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
class CJTPackagesManagerListTable extends WP_List_Table {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Set hook suffix (E_ALL complain)!
		$GLOBALS['hook_suffix'] = 'cjt';
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
				echo "<input type='checkbox' name='id[]' value='{$item->id}' />";
			break;
			case 'name':
				// Package name!
				$value  = "<span class='package-name'>{$item->{$name}}</span>";
				// Description underneath the name!
				$value .= "<br /><div class='description'><span>{$item->description}</span></div>";
				// Display row actions underneath template name column.
				$actions = array();
				// ----$actions['info'] = "<a href='#info({$item->id})'>" . cssJSToolbox::getText('Info') . '</a>';
				$actions['delete'] = "<a href='#delete({$item->id})'>" . cssJSToolbox::getText('Delete') . '</a>';
				// Show actions row underneath template name!!
				$value .= $this->row_actions($actions, false);
			break;
			case 'webSite':
				$value = "<a target='_blank' href='{$item->$name}'>{$item->$name}</a>";
			break;
			case 'license':
			case 'readme':
				if ($item->$name) {
					// Upper casxe first letter.
					$fileName = ucfirst($name);
					// Generate view file link for each file name (readmem license)!
					$value = "<a class='view-package-file' href='#get{$fileName}File({$item->id})'>" . cssJSToolbox::getText('View') . "</a>";
				}
				else {
					$value = cssJSToolbox::getText('N/A');
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
	*/
	public function get_bulk_actions() {
		// Bulk ations.
		$actions = array(
			'delete' => cssJSToolbox::getText('Delete'),
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
			'author' => cssJSToolbox::getText('Author'),
			'webSite' => cssJSToolbox::getText('Website'),
			'license' => cssJSToolbox::getText('License'),
			'readme' => cssJSToolbox::getText('Readme'),
			'id' => cssJSToolbox::getText('ID'),
		);
	}
	
} // End class.