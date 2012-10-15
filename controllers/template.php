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
class CJTTemplateController extends CJTAjaxController {

	/**
	* 
	* Initialize new object.
	* 
	* @return void
	*/
	public function __construct($controllerInfo) {
		// Initialize parent!
		parent::__construct($controllerInfo);
		// Add actions.
		$this->registryAction('edit');
		$this->registryAction('update');
	}
	
	
	/**
	* put your comment there...
	* 
	*/
	public function editAction() {
		$this->model->inputs['guid'] = $_GET['guid'];
		// Get view layout!
		$layout = isset($_REQUEST['layout']) ? $_REQUEST['layout'] : 'default';
		// Display the view.
		$this->httpContentType = 'text/html';
		ob_start();
		$this->view->display($layout);
		$this->response =  ob_get_clean(); 
	}
	
	/**
	* put your comment there...
	* 	
	*/
	public function updateAction() {
		$item = $_REQUEST['item'];
		if (!is_array($item)) {
			throw new Exception('Invalid item data');
		}
		// Cast item array to object.
		$item = (object) $item;
		if ($this->model->update($item)) {
			$this->response = array('guid' => $item->guid);
		}
	}
	
} // End class.