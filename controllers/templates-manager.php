<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesManagerController extends CJTAjaxController {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'templates-manager', 'view' => 'templates/manager');
	
	/**
	* 
	* Initialize new object.
	* 
	* @return void
	*/
	public function __construct() {
		// Initialize parent!
		parent::__construct();
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
		// Set default filters.
		if (!$_REQUEST['filter_states']) {
			$_REQUEST['filter_states'] = 'published';
		}
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