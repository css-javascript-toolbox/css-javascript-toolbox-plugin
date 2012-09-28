<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesModel {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize CTTTable MYSQL Driver.
		$this->dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function get($columns = array('*'), $filter = array(), $order = array(), $limits = array()) {
		// Import Templates-List SQL view.
		require_once CJTOOLBOX_TABLES_PATH . '/templates-list-view.php';
		$templatesView = new CJTTemplatesListSQLView($this->dbDriver);
		// Set view parameters.
		$templatesParameters =& $templatesView->getQueryObject();
		$templatesParameters->columns = $columns;
		$templatesParameters->filter = $filter;
		$templatesParameters->orderBy = $order;
		$templatesParameters->limits = $limits;
		// Query templates.
		$templates = $templatesView->exec();
		return $templates;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		$this->dbDriver->processQueue();
	}
	
} // End class.