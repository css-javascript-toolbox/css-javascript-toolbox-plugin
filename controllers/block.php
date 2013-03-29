<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

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
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'x-block', 'model_file' => 'xblock');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Actions!
		$this->registryAction('getBlockBy');
	}
	
	/**
	* Query single block based on the provided criteria!
	* 
	*/
	public function getBlockByAction() {
		// Initialize.
		$returns = array_flip($_GET['returns']);
		// Set inputs.
		$inputs =& $this->model->inputs;
		$inputs['filter'] = $_GET['filter'];
		// Query Block.
		$this->response = array_intersect_key((array) $this->model->getBlockBy(), $returns);
	}
	
} //  End class.
