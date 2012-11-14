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
class CJTTemplatesManagerController extends CJTAjaxController {

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
		$this->registryAction('display');
		$this->registryAction('delete');
		$this->registryAction('changeState');
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function displayAction() {
		$this->model->inputs = $_REQUEST;
		// Display view.
		parent::displayAction();	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function deleteAction() {
		$this->model->inputs['ids'] = $_GET['ids'];
		$this->model->delete();
		// Response with changed ids.
		$this->response['changes'] = $this->model->inputs['ids'];		
	}
	
	/**
	* put your comment there...
	* 
	*/	
	protected function changeStateAction() {
		$this->model->inputs['ids'] = $_GET['ids'];
		$this->model->inputs['state'] = $_GET['params'];
		$this->model->changeState();
		// Response with changed ids.
		$this->response['changes'] = $this->model->inputs['ids'];
	}
	
} // End class.