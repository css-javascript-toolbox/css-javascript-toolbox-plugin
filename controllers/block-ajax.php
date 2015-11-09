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
		add_action('wp_ajax_cjtoolbox_get_revision', array(&$this, '_doAction'));
		add_action('wp_ajax_cjtoolbox_get_revisions', array(&$this, '_doAction'));
		// Redirects
		$this->registryAction('getBlockBy');
		$this->registryAction('getAPOP');
		$this->registryAction('restoreRevision');
		$this->registryAction('loadUrl');
		$this->registryAction('downloadCodeFile');
	}

	/**
	* put your comment there...
	* 
	* @deprecated this is just a redirect to the CJTBlockContoller::getAction().
	*/
	protected function downloadCodeFileAction() {
		// Pass to CJTBlockController!
		$this->redirect('block');
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
	* put your comment there...
	* 
	* @deprecated All will be moved to other controllers in the future versions.
	*/
	public function getRevisionAction() {
		// Initialize
		$model = $this->getModel('blocks');
		$tblCodeFile = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		// Inputs.
		$revisionId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		// Get request parameters.
		$revision['id'] = $revisionId;
		$revision['fields'] = array('id', 'links', 'expressions', 'masterFile');
		$revision = $model->getBlock($revision['id'], array(), $revision['fields']);
		// Get revisioned code file.
		$revision->code = $tblCodeFile->set('blockId', $revisionId)
																	->set('id', $revision->masterFile)
																	->load()
																	->getData()->code;
		// Discard Pins.
		$customPins = array_keys( CJTBlockModel::getCustomPins() );
		
		foreach ( $customPins as $customPinName )
		{
			$revision->{$customPinName} = false;
		}
		
		// Return revision.
		$this->response = $revision;
	}
	
	/**
	* put your comment there...
	* 
	* @deprecated All will be moved to other controllers in the future versions.
	*/
	public function getRevisionsAction() {
		// initialize.
		$model = $this->getModel('blocks');
		// Get request parameters.
		$blockId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		// Get block revisions.
		$revisions['filter']['masterFile'] = $_GET['activeFileId'];
		$revisions['filter']['parent'] = $blockId;
		$revisions['filter']['type'] = 'revision';
		// Its mandatory to select fields instead of using just .*.
		// This is because id field must be first to be used as the array key
		$revisions['fields'] = array('id', 'name', 'created', 'lastModified', 'owner');
		// Query getBlocks without ids filter or backup.
		$revisions = $model->getBlocks(null, $revisions['filter'], $revisions['fields']);
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
	* put your comment there...
	* 
	* @deprecated this is just a redirect to the CJTBlockContoller::getAction().
	*/
	protected function loadUrlAction() {
		// Pass to CJTBlockController!
		$this->redirect('block');
	}

	/**
	* put your comment there...
	* 
	*/
	protected function restoreRevisionAction() {
		// Initialize.
		$mdlBlocks = new CJTBlocksModel();
		$tblCodeFile = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		// Get revision ID.
		$rId = (int) $_GET['rid'];
		$bId = (int) $_GET['bid'];
		// Get Revision Block + Revision Code.
		$revisionBlock = $mdlBlocks->getBlock($rId, array(), array('id', 'pinPoint', 'links', 'expressions', 'masterFile'));
		$revisionBlock->code = $tblCodeFile->set('blockId', $rId)
																			 ->set('id', $revisionBlock->masterFile)
																			 ->load()
																			 ->getData()->code;
		// If code === null set it to empoty string '' as null woulod
		// prevent the field from being in the query, cause SQL error.
		if ($revisionBlock->code === null) {
			$revisionBlock->code = '';
		}
		// Code File Fields.
		$revisionBlock->activeFileId = $revisionBlock->masterFile;
		$revisionBlock->masterFile = null; // This is just for querying CodeFile. DONT UPDATE.
		// Restore Block.
		$revisionBlock->id = $bId;
		$mdlBlocks->update($revisionBlock, true);
		$mdlBlocks->save();
		// Return TRUE.
		$this->response = true;
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