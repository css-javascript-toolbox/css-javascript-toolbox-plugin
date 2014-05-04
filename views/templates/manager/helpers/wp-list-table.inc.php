<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import WP_List_Table class.
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
cssJSToolbox::import('tables:template.php');

/**
* 
*/
class CJTTemplatesManagerListTable extends WP_List_Table {
	
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
			case 'state':
			case 'type':
				$value = cssJSToolbox::getText($item->{$name});
			break;
			case 'name':
				// Display cell value as regular.
				$value  = "<span class='template-name'>{$item->{$name}}</span>";
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
				// Allow Deletion Only if state = trash!
				if ($item->state == 'trash') {
					$actions['delete'] = "<a href='#delete({$item->id})'>" . cssJSToolbox::getText('Delete') . '</a>';	
				}
				// Show only states that the Template s not in!
				$states = CJTTemplateTable::$states;
				unset($states[$item->state]);
				foreach ($states as $name => $text) {
					$actions[$name] = "<a href='#changeState({$item->id})'>{$text}</a>";
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
		// Import dependencies.
		cssJSToolbox::import('framework:html:list.php');
		// Define filters.		
		$filters = array();
		$filters['states'] = 'State';
		$filters['types'] = 'Type';
		$filters['authors'] = 'Author';
		$filters['versions'] = 'Version';
		$filters['creation-dates'] = 'Date Created';
		$filters['development-states'] = 'Release';
		$filters['last-modified-dates'] = 'Last Modified';
		// Get the HTML field for each filter antput the result.
		$filtersName = array();
		foreach ($filters as $name => $text) { 
			// Output field markup.
			$fieldName = "filter_{$name}";
			$classes = "filter filter_{$name}";
			echo CJTListField::getInstance("template-{$name}", 
					'templates-manager', 
					$fieldName, 
					isset($_REQUEST[$fieldName]) ? $_REQUEST[$fieldName] : null, 
					null, 
					$classes,
					null,
					null,
					null,
					cssJSToolbox::getText($text)
				)->getInput();
			// Complete list of filters names!
			$filtersName[] = $fieldName;
		}
		if ($which == 'top') {
			// Output all filter names list!
			$filtersName = implode(',', $filtersName);
			echo "<input type='hidden' name='allFiltersName' value='{$filtersName}' />";			
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
			'changeState::published' => cssJSToolbox::getText('published'),
			'changeState::trash' => cssJSToolbox::getText('trash'),
			'changeState::draft' => cssJSToolbox::getText('draft'),
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
			'developmentState' => cssJSToolbox::getText('Release'),
			'author' => cssJSToolbox::getText('Author'),
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
	
} // End class.