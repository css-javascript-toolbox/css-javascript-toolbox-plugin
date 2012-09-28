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
	}
	  
	/**
	* Display templates manager form.
	* 
	*/
	protected function displayAction() {
		// Display the view.
		$this->httpContentType = 'text/html';
		$this->response = $this->view->getTemplate('default');
	}
	
} // End class.