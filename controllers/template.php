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
		$this->registryAction('save');
	}
	
	
	/**
	* put your comment there...
	* 
	*/
	public function editAction() {
		$this->model->inputs['id'] = (int) $_REQUEST['id'];
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
	public function saveAction() {
		$this->model->inputs['item'] = $_REQUEST['item'];
		if ($revision = $this->model->save()) {
			$this->response = array('revision' => $revision);
		}
	}
	
} // End class.