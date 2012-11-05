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
		$this->registryAction('display');
		$this->registryAction('embedded');
		$this->registryAction('link');
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function embeddedAction() {
		// Read inputs.
		$this->model->inputs['templateId'] = $_REQUEST['templateId'];
		$this->model->inputs['blockId'] = $_REQUEST['blockId'];
		// Get embedded template code!
		if ($revision = $this->model->embedded($code)) {
			$this->response['templateId'] = $revision->get('templateId');
			$this->response['revisionId'] = $revision->get('id');
			$this->response['code'] = $code;
		}
		else {
			$this->httpContentType = 'text/html';
			$this->response = cssJSToolbox::getText('Error Embedding Template!!!');
		}
	}

/**
* put your comment there...
* 	
*/
	protected function linkAction() {
		
	}
	
} // End class.