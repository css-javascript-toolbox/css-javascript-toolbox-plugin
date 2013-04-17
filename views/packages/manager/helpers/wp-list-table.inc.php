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
			case 'name':
				// Display cell value as regular.
				$value  = "<span class='package-name'>{$item->{$name}}</span>";
				// Show description. Truncate description/display breif desc.
				$tweentyChunks = array_chunk(explode(' ', $item->description), 20);
				$brief = implode(' ', array_shift($tweentyChunks));
				// Final text!
				$value .= '<br />';
				$value .= "<div class='description'><span>{$brief}</span>";
				if (strlen($brief) < strlen($item->description)) {
					$value .= '.... ';
				}
				$value .= '</div>';
				// Display row actions underneath template name column.
				$actions = array();
				$actions['info'] = "<a href='#info({$item->id})'>" . cssJSToolbox::getText('Info') . '</a>';
				$actions['edit'] = "<a href='#edit({$item->id})'>" . cssJSToolbox::getText('Edit') . '</a>';
				$actions['delete'] = "<a href='#delete({$item->id})'>" . cssJSToolbox::getText('Uninstall') . '</a>';	
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
	*/
	public function get_bulk_actions() {
		// Bulk ations.
		$actions = array(
			'uninstall' => cssJSToolbox::getText('Uninstall'),
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
			'uri' => cssJSToolbox::getText('URI'),
			'objectsCount' => cssJSToolbox::getText('Object Count'),
			'id' => cssJSToolbox::getText('ID'),
		);
	}
	
} // End class.