<?php
/**
* 
*/

/**
* This class should replace any other controllers that
* has methods for interacting with a single Block (e.g block-ajax!)
* 
* All single Block actions (e.g edit, new and save) should be placed/moved here
* in the future!
*/
class CJTBlockController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @return CJTBlockController
	*/
	public function __construct($info) {
		parent::__construct($info);
		// Actions!
		$this->registryAction('get');
	}
	
	/**
	* Query single block based on the provided criteria!
	* 
	*/
	public function getAction() {
		// Initialize.
		$returns = array_flip($_GET['returns']);
		// Set inputs.
		$inputs =& $this->model->inputs;
		$inputs['filter'] = $_GET['filter'];
		// Query Block.
		$this->response = array_intersect_key($this->model->get(), $returns);
	}
	
} //  End class.
