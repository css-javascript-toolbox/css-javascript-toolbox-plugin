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
		// Initialize.
		WP_Filesystem();
		// initialize vars.
		$wpFileSystem =& $GLOBALS['wp_filesystem'];
		$ids = array();
		$dbDriver = cssJSToolbox::getInstance()->getDBDriver();
		$fsConfig = cssJSToolbox::$config->fileSystem;
		// Import dependencies.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		CJTxTable::import('template');
		// Delete only templates in "trash" state!
		// For more security don't even delete templates with SYSTEM Attribute flag
		// is turned ON!
		$sysFlag = CJTTemplateTable::ATTRIBUTES_SYSTEM_FLAG;
		$idsQueryList = implode(',', $this->inputs['ids']);
		$templates = $dbDriver->select("SELECT id, queueName `directory`
																														FROM #__cjtoolbox_templates 
																														WHERE ID IN ({$idsQueryList}) AND ((attributes & {$sysFlag}) = 0) AND (`state` = 'trash')");
		if (!empty($templates)) {
			// Deleing template directory files.
			foreach ($templates as $template) {
				// Absolute path to template directory!
				$templateDirectoryAbsPath = WP_CONTENT_DIR . "/{$fsConfig->contentDir}/{$fsConfig->templatesDir}/{$template->directory}";
				// Delete template directory RECUSIVLY!
				$wpFileSystem->rmdir($templateDirectoryAbsPath, true);
			}
			// Get templates IDs to delete.
			$ids = implode(', ', array_keys($templates));
			// Permenantly delete all templates data from
			// templates table and all refernced tables.
			$dbDriver->startTransaction()
														->delete("DELETE FROM #__cjtoolbox_block_templates WHERE templateId IN ({$ids})")
														->delete("DELETE FROM #__cjtoolbox_template_revisions WHERE templateId IN ({$ids})")
														->delete("DELETE FROM #__cjtoolbox_templates WHERE id IN ({$ids})")
														->commit()
														->processQueue();
		}
		return $ids;
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
		$orderBy = isset($this->inputs['orderby']) ? " ORDER BY {$this->inputs['orderby']} {$this->inputs['order']}" : '';
		// final query.
    $query = "{$select}{$queryBase['from']}{$queryBase['where']}{$orderBy}{$limit}";
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
				$field['table'] = isset($field['table']) ? "{$field['table']}." : '';
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
		$query = "{$select}{$queryBase['from']}{$queryBase['where']}";
		// Get items total.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$result = $dbDriver->select($query);
		return array_shift($result)->Total;
	}
	
} // End class.