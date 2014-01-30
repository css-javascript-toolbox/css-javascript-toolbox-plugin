<?php
/**
* @version $ Id; block-ajax.php 21-03-2012 03:22:10 Ahmed Said $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* Server single block OR block specific actions.
* 
* @author Ahmed Said
* @version 6
* @deprecated DONT ADD MORE ACTIONS. USE CJTBlockController and other controllers instead!
*/
class CJTBlockAjaxController extends CJTAjaxController {
	
	/**
	* Initialize controller object.
	* 
	* @see CJTController for more details
	* @return void
	*/
	public function __construct() {
		parent::__construct();
		// Supported actions.
		add_action('wp_ajax_cjtoolbox_get_info_view', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_set_property', array(&$this, '_doAction'));
		// Redirects
		$this->registryAction('getBlockBy');
		$this->registryAction('getAPOP');
	}

	
	/**
	* put your comment there...
	* 
	* @deprecated this is just a redirect to the CJTBlockContoller::getAction().
	*/
	protected function getAPOPAction() {
		// Pass to CJTBlockController!
		$this->redirect('block');
	}

	/**
	* put your comment there...
	* 
	* @deprecated this is just a redirect to the CJTBlockContoller::getAction().
	*/
	protected function getBlockByAction() {
		// Pass to CJTBlockController!
		$this->redirect('block');
	}
	
	/**
	* put your comment there...
	* 
	* @deprecated All will be moved to other controllers in the future versions.
	*/
	public function getInfoViewAction() {
		$model = $this->getModel('blocks');
		// Set content type as HTML.
		$this->httpContentType = "text/html";
		// Get block info.
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$Info = $model->getInfo($id);
		// Create info view object.
		$view = CJTController::getView('blocks/info');
		// Get view info content.
		$view->info = $Info;
		$this->response = $view->getTemplate('default');
	}

	/**
	* Update exists block property value.
	* 
	* 
	* Call this method using POST method with the following parameters.
	* 	- id integer Block id.
	* 	- property string Property Name to change.
	* 	- value mixed Property value to change.
	* 
	* Response body is array with the following elements.
	* 	- string oldValue Old property value.
	* 	- string value New value.
	* 
	* @deprecated
	* @return void
	*/
	public function setPropertyAction() {
		// Initialize.
		$response = array();
		$blocks = $this->getModel('blocks');
		// Prepare parameters.
		$blockId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
		$property = filter_input(INPUT_POST, 'property', FILTER_SANITIZE_STRING);
		$newValue = filter_input(INPUT_POST, 'value', FILTER_UNSAFE_RAW);
		// Get old value.
		$block = $blocks->getBlock($blockId);
		$oldValue = $block->$property;
		// Update only if there is a change.
		if ($oldValue != $newValue) {
			// Update block property.
			$block->$property = $newValue;
			$blocks->setBlock($block);
			$blocks->save();
			// Set response object.
			$response['oldValue'] = $oldValue;
			$response['value'] = $newValue;
		}
		$this->response = $response;
	}
	
} // End class.