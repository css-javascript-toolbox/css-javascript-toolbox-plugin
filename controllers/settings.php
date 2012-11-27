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
class CJTSettingsController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'settings');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Actions.
		$this->registryAction('manageForm');
		$this->registryAction('save');
		$this->registryAction('restoreDefault');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function manageFormAction() {
		// Create settings view.
		$view = self::getView('settings/manager');
		// Push settings into the view.
		$view->settings = $this->model;
		// Output settings view.
		$this->httpContentType = 'text/html';
		$this->response = $view->getTemplate('settings');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function restoreDefaultAction() {
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function saveAction() {
		// Get settings page parameters.
		$settings = filter_input(INPUT_POST, 'settings', FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$this->model->save($settings);
		$this->response = true;
	}
	
} // End class.