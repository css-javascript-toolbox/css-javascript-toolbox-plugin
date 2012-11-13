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
		$this->registryAction('delete');
		$this->registryAction('display');
		$this->registryAction('publish');
		$this->registryAction('trash');
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
		
	}
	
	/**
	* put your comment there...
	* 
	*/	
	protected function changeStateAction() {
		
	}
	
} // End class.