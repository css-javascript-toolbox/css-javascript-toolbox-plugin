<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTemplatesManagerModel {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $inputs;
	
	/**
	* put your comment there...
	* 
	*/
	public function changeState() {
		$ids = implode(',', $this->inputs['ids']);
		// initialize vars.
		cssJSToolbox::getInstance()->getDBDriver()
																->update("UPDATE #__cjtoolbox_templates 
																											SET `state` = '{$this->inputs['state']}'
																											WHERE id IN ({$ids})")
																->processQueue();
		return $this;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function delete() {
		// initialize vars.
		$dbDriver = cssJSToolbox::getInstance()->getDBDriver();
		$ids = implode(',', $this->inputs['ids']);
		// Delete only templates in "trash" state!
		$ids = $dbDriver->select("SELECT id 
																														FROM #__cjtoolbox_templates 
																														WHERE ID IN ({$ids}) AND `state` = 'trash'");
		$ids = implode(', ', array_keys($ids));
		// Permenantly delete all templates data from
		// templates table and all refernced tables.
		$dbDriver->startTransaction()
													->delete("DELETE FROM #__cjtoolbox_block_templates WHERE templateId IN ({$ids})")
													->delete("DELETE FROM #__cjtoolbox_template_revisions WHERE templateId IN ({$ids})")
													->delete("DELETE FROM #__cjtoolbox_templates WHERE id IN ({$ids})")
													->commit()
													->processQueue();
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		// Build query.
		$select = 'SELECT t.id, 
																					t.name, 
																					t.type, 
																					t.description, 
																					t.creationDate,
																					t.state, 
																					a.name author,
																					r.dateCreated lastModified,
																					r.version,
																					r.state developmentState';
		$queryBase = $this->getItemsQuery();
		// Paging.
		$itemsPerPage = $this->getItemsPerPage();
		// Get page no#.
		$page = !isset($this->inputs['paged']) ? 1 : $this->inputs['paged'];
		// Calculate start offset.
		$start = ($page - 1) * $itemsPerPage;
		$limit = " LIMIT {$start},{$itemsPerPage}";	
		// Order.
		if (isset($this->inputs['orderby'])) {
			$orderBy = " ORDER BY {$this->inputs['orderby']} {$this->inputs['order']}";
		}
		// final query.
    $query = "{$select}{$queryBase['from']}{$queryBase['where']}{$queryBase['groupBy']}{$orderBy}{$limit}";
		// Execute our query using MYSQL queue driver.
		$result = cssJSToolbox::getInstance()->getDBDriver()->select($query);
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsPerPage() {
		return 20;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsQuery() {
		// Import dependencies.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		CJTxTable::import('template-revision');
		CJTxTable::import('author');
		// From clause.
		$query['from'] = ' FROM #__cjtoolbox_templates t
													LEFT JOIN #__cjtoolbox_template_revisions r ON t.id = r.templateId
													LEFT JOIN #__cjtoolbox_authors a ON t.authorId = a.id';
		// Always get only the last revision.
		$where[] = '(r.attributes & ' . CJTTemplateRevisionTable::FLAG_LAST_REVISION . ')';
		// For version 6 don't display Internal/System (e.g Wordpress, ) Authors templates
		$where[] = '((a.attributes & ' . CJTAuthorTable::FLAG_SYS_AUTHOR . ') = 0)';
		// Build where clause based on the given filters!
		$filters = array(
			'types' => array('table' => 't', 'name' =>'type'), 
			'authors' => array('table' => 't', 'name' => 'authorId'),
			'version' => array('table' => 'r', 'name' => 'version'),
			'creation-dates' => array( 'name' => 'DATE(creationDate)'),
			'last-modified-dates' => array('name' => 'DATE(dateCreated)'),
			'states' => array('table' => 't', 'name' => 'state'),
			'development-state' => array('table' => 'r', 'name' => 'state'),
		);
		foreach ($filters as $name => $field) {
			$filterName = "filter_{$name}";
			// Add filter only if there is a value specified.
			if (!empty($this->inputs[$filterName])) {
				$value = $this->inputs[$filterName];
				if (!is_numeric($value)) {
					$value = "'{$value}'";
				}
				if ($field['table']) {
					$field['table'] .= '.';
				}
				$where[] = "{$field['table']}{$field['name']} = {$value} ";
			}
		}
		$query['where'] = ' WHERE ' .  implode(' AND ', $where);	
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsTotal() {
		$queryBase = $this->getItemsQuery();
		$select = 'SELECT count(*) Total';
		$query = "{$select}{$queryBase['from']}{$queryBase['where']}{$queryBase['groupBy']}";
		// Get items total.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return array_shift($result)->Total;
	}
	
} // End class.