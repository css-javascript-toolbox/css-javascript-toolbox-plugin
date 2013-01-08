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
class CJTTemplatesLookupController extends CJTAjaxController {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'templates-lookup', 'view' => 'templates/lookup');
	
	/**
	* 
	* Initialize new object.
	* 
	* @return void
	*/
	public function __construct() {
		parent::__construct();
		// Registry controller actions.
		$this->registryAction('display');
		$this->registryAction('embedded');
		$this->registryAction('link');
		$this->registryAction('unlink');
		$this->registryAction('unlinkAll');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	protected function displayAction($tpl = null) {
		// Prepare inputs
		$this->model->inputs['blockId'] = $_REQUEST['blockId'];
		// Display view!
		parent::displayAction();
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
		$this->response['code'] = $this->model->embedded();
	}

/**
* put your comment there...
* 	
*/
	protected function linkAction() {
		// Read inputs.
		$this->model->inputs['templateId'] = $_REQUEST['templateId'];
		$this->model->inputs['blockId'] = $_REQUEST['blockId'];		
		// Link template!
		$this->model->link();
		// Response with new state!
		$this->response['newState']  = array(
			'action' => 'unlink',
			'text' => cssJSToolbox::getText('Unlink'),
			'className' => 'template-action unlink-template'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function unlinkAction() {
		// Read inputs.
		$this->model->inputs['templateId'] = $_REQUEST['templateId'];
		$this->model->inputs['blockId'] = $_REQUEST['blockId'];		
		// Link template!
		$this->model->unlink();
		// Response with new state!
		$this->response['newState']  = array(
			'action' => 'link',
			'text' => cssJSToolbox::getText('Link'),
			'className' => 'template-action link-template'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function unlinkAllAction() {
		// Read inputs!
		$this->model->inputs['blockId'] = $_REQUEST['blockId'];
		$this->model->unlinkAll();
		// Response with new state!
		$this->response['newState']  = array(
			'action' => 'link',
			'text' => cssJSToolbox::getText('Link'),
			'className' => 'template-action link-template'
		);
	}
	
} // End class.