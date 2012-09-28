<?php
/**
* @version $ Id; block-ajax.php 21-03-2012 03:22:10 Ahmed Said $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Server single block OR block specific actions.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlockAjaxController extends CJTAjaxController {
	
	/**
	* Initialize controller object.
	* 
	* @see CJTController for more details
	* @return void
	*/
	public function __construct($controllerInfo) {
		parent::__construct($controllerInfo);
		// Supported actions.
		add_action('wp_ajax_cjtoolbox_get_info_view', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_set_property', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_get_revision', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_get_revisions', array(&$this, '_doAction'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInfoViewAction() {
		// Set content type as HTML.
		$this->httpContentType = "text/html";
		// Get block info.
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$Info = $this->model->getInfo($id);
		// Create info view object.
		$view = CJTController::getView('blocks/info');
		// Get view info content.
		$view->info = $Info;
		$this->response = $view->getTemplate('default');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getRevisionAction() {
		// Get request parameters.
		$revision['id'] = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$revision['fields'] = array('id', 'code', 'pinPoint', 'links', 'expressions');
		$revision = $this->model->getBlock($revision['id'], array(), $revision['fields']);
		$this->response = $revision;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getRevisionsAction() {
		// Get request parameters.
		$blockId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		// Get block revisions.
		$revisions['filter']['parent'] = $blockId;
		$revisions['filter']['type'] = 'revision';
		// Its mandatory to select fields instead of using just .*.
		// This is because id field must be first to be used as the array key
		$revisions['fields'] = array('id', 'name', 'created', 'lastModified');
		// Query getBlocks without ids filter or backup.
		$revisions = $this->model->getBlocks(null, $revisions['filter'], $revisions['fields']);
		// Create view.
		$view = $this->getView('blocks/revisions');
		// Push view vars.
		$view->blockId = $blockId;
		$view->revisions = $revisions;
		// Set output header.
		$this->httpContentType = 'text/html';
		// Return view content.
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
		$blocks = $this->model;
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