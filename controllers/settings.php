<?php
/**
* 
*/

// No direct access allowed.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTSettingsController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($info) {
		parent::__construct($info);
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