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
		$this->registryAction('info');
	}
	
	
	/**
	* put your comment there...
	* 
	*/
	public function editAction() {
		$this->model->inputs['id'] = (int) $_REQUEST['id'];
		// Display the view.
		parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function infoAction() {
		$this->model->inputs['id'] = (int) $_REQUEST['id'];
		// Display the view.
		parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 	
	*/
	public function saveAction() {
		if (!$rawInput = file_get_contents('php://input')) {
			throw new Exception('Could not read RAW input DATA!!!');
		}
		// Get RAW input for all text fields avoid magic_quotes and this poor stuff!
		parse_str($rawInput, $rawInput);
		// Posted template data is in the item array, the others is just for making the request!
		$this->model->inputs['item'] = $rawInput['item'];
		if ($revision = $this->model->save()) {
			$this->response = array('revision' => $revision);
		}
	}
	
} // End class.