<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* 
*/
class CJTsetupController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'setup');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Register actions!
		$this->registryAction('activationFormView');
		$this->registryAction('activate');
		$this->registryAction('check');
		$this->registryAction('getComponentLicenseState');
	}
	
	/**
	* Activate single component!
	* 
	* @return void
	*/
	protected function activateAction() {
		// Activate license!
		$state = $this->model->activate($_REQUEST['component'], $_REQUEST['license']);
		// Return message returned fom the request.
		$this->response = $state;
	}
	
	/**
	* Display Activation form!
	* 
	*/
	protected function activationFormViewAction() {
		// Push component info to model.
		$this->model->setInputs($_REQUEST['component']);
		// Display activation for for the requested component!
		parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function checkAction() {
		// Activate license!
		$state = $this->model->check($_REQUEST['component'], $_REQUEST['license']);
		// Return message returned fom the request.
		$this->response = $state;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getComponentLicenseStateAction() {
		$this->response = $this->model->getComponentLicenseType($_REQUEST['component'], 'state');
	}
	
} // End class.
