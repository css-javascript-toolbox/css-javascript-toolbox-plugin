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
		$this->registryAction('getAPOP');
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
	
	/**
	* Get assigment panel objects page.
	* 
	*/
	public function getAPOPAction() {
		// Read inputs.
		$iPerPage = (int) $_GET['iPerPage'];
		$blockId = (int) $_GET['block'];
		$oTypeParams = $_GET['typeParams'];
		$offset = $_GET['index'];
		// Get the corresponding type object
		// for handling the request.
		$typeName = $oTypeParams['targetType'];
		/**
		* put your comment there...
		* 
		* @var CJT_Models_Block_Assignmentpanel_Base
		*/
		$typeObject = CJT_Models_Block_Assignmentpanel_Base
								::getInstance($typeName,
															$offset, 
															$iPerPage, 
															$blockId, 
															$oTypeParams);
		// Fetch next page.
		$items = $typeObject->getItems();
		// Return result
		$this->response['count'] = count($items);
		$this->response['items'] = $items;
	}

} //  End class.
