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
class CJTTemplateController extends CJTAjaxController {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'template');
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onsave = array('parameters' => array('data'));
	
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
		$this->registryAction('edit');
		$this->registryAction('save');
		$this->registryAction('info');
		$this->registryAction('getTemplateBy');
	}

	/**
	* put your comment there...
	* 
	*/
	protected function editAction() {
		$this->model->inputs['id'] = (int) $_REQUEST['id'];
		// Display the view.
		parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getTemplateByAction() {
		// Initialize.
		$returns = array_flip($_GET['returns']);
		// Set inputs.
		$inputs =& $this->model->inputs;
		$inputs['filter'] = $_GET['filter'];
		// Query Block.
		$this->response = array_intersect_key((array) $this->model->getTemplateBy(), $returns);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function infoAction() {
		$this->model->inputs['id'] = (int) $_REQUEST['id'];
		// Display the view.
		parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 	
	*/
	protected function saveAction() {
		// Read inputs
		$item = filter_input(INPUT_POST, 'item', FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		// Posted template data is in the item array, the others is just for making the request!
		$this->model->inputs['item'] = $this->onsave($item);
		if ($revision = $this->model->save()) {
			$this->response = array('revision' => $revision);
		}
	}
	
} // End class.

// Hookable!
CJTTemplateController::define('CJTTemplateController', array('hookType' => CJTWordpressEvents::HOOK_FILTER));