<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

/**
* No direct access.
*/
// No Direct Accesss code

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesLookupController extends CJTAjaxController {

	/**
	* 
	* Initialize new object.
	* 
	* @return void
	*/
	public function __construct($controllerInfo) {
		parent::__construct($controllerInfo);
		// Registry controller actions.
		$this->registryAction('list');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function listAction() {
		// Create Templates Lookup view object.
		$view = self::getView('templates/lookup');
		// Get all templates.
		$templates = $this->model->get();
		// Push templates into the view.
		$view->templates = $templates;
		// Return templates list.
		$this->httpContentType = 'text/html';
		$this->response = $view->getTemplate('default');
	}
	
} // End class.