<?php
/**
* @version $ Id; blocks-backups.php 21-03-2012 03:22:10 Ahmed Said $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
*
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlocksBackupsController extends CJTAjaxController {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'blocks-backups');
	
	/**
	* Initialize controller object.
	* 
	* @see CJTController for more details
	* @return void
	*/
	public function __construct() {
		parent::__construct();
		// Supported actions.
		add_action('wp_ajax_cjtoolbox_create', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_delete', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_list', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_restore', array(&$this, '_doAction'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function createAction() {
		$backupData = array();
		// Get posted backup data.
		$backupData['name'] = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
		$backupRowIndex = filter_input(INPUT_GET, 'rowIndex', FILTER_SANITIZE_NUMBER_INT);
		// Create new backup -- Data will be retruned along with new backup Id.
		$backupData = $this->model->create($backupData);
		$this->model->save();
		// Get single backup row.
		$view = self::getView('backups/manager');
		$view->currentBackup = (object) $backupData;
		// Send response back to client.
		$this->httpContentType = 'text/html';
		$this->response = $view->getTemplate('single-backup', array('rowIndex' => $backupRowIndex));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function deleteAction() {
		// Get backup id from request parameters.
		$backupId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		// Delete backup.
		$this->model->delete($backupId);
		$this->model->save();
		// Get backups list .
		$view = self::getView('backups/manager');
		$view->backups = (object) $backupData;
	}
	
	/**
	* put your comment there...
	* 
	* @todo Accept template name as parameters and also work
	* if parameter is no passed!
	*/
	public function listAction($templateName = 'default') {		
		// Create backup view.
		$view = self::getView('backups/manager');
		// Push vars into view.
		$view->backups = $this->model->getAll();
		$view->controllerName = 'blocksBackups';
		// Set response header.
		$this->httpContentType = 'text/html';
		// Send view content back to client.
		$this->response = $view->getTemplate($templateName);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function restoreAction() {
		// Get backup id from request parameters.
		$backupId = filter_input(INPUT_GET, 'backupId', FILTER_SANITIZE_NUMBER_INT);		
		$this->model->restore($backupId);
		// Save changes into the database.
		$this->model->save();
	}
	
} // End class.